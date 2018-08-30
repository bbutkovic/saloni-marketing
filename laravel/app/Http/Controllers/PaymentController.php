<?php

namespace App\Http\Controllers;

use App\Models\Booking\Booking;
use App\Models\Salon\SalonPaymentOptions;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use PayPal\Api\Amount;
/** All Paypal Details class **/
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

use PayPal\Api\Refund;
use PayPal\Api\RefundRequest;

class PaymentController extends Controller {

    private $api_context;

    public function __construct() {
        $this->payment_repo = new PaymentRepository();
    }

    public function payWithpaypal(Request $request) {

        //get the booking info for payment data (amount and currency to be charged to the client)
        $charging_amount = $this->payment_repo->getChargingAmount($request->booking_id);
        $booking = Booking::find($request->booking_id);
        $salon = $booking->booking_location->salon;
        $paypal_payment = SalonPaymentOptions::where('salon_id', $salon->id)->where('payment_gateway', 'paypal')->first();

        $paypal_conf = [
            'client_id' => $paypal_payment->public_key,
            'secret' => $paypal_payment->private_key,
            'settings' => [
                'mode' => env('PAYPAL_MODE','sandbox'),
                'http.ConnectionTimeOut' => 30,
                'log.LogEnabled' => true,
                'log.FileName' => storage_path() . '/logs/paypal.log',
                'log.LogLevel' => 'ERROR'
            ],
        ];

        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_payment->public_key,
                $paypal_payment->private_key
            )
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

        if($charging_amount['status'] === 1) {

            $price = $charging_amount['charge_amount'];

            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $amount = new Amount();
            $amount->setCurrency('USD')
                ->setTotal($price);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
                ->setDescription('Online payment - Saloni Marketing');

            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(URL::route('paypalStatus', $request->booking_id))
                ->setCancelUrl(URL::route('paypalStatus', $request->booking_id));

            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));

            try {

                $payment->create($this->_api_context);

            } catch (\PayPal\Exception\PPConnectionException $ex) {

                if (\Config::get('app.debug')) {
                    return redirect()->route('clientAppointments')->with('error_message', 'Connection timeout');
                } else {
                    \Session::put('error', 'Some error occur, sorry for inconvenient');
                    return redirect()->route('clientAppointments')->with('error_message', $ex->getMessage());
                }
            }

            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }

            \Session::put('paypal_payment_id', $payment->getId());
            if (isset($redirect_url)) {
                /** redirect to paypal **/
                return Redirect::away($redirect_url);
            }
            \Session::put('error', 'Unknown error occurred');
            return redirect()->route('paywithpaypal');
        }

        return redirect()->back()->with('error_message', $charging_amount['message']);
    }

    public function getPaymentStatus($booking_id) {

        $booking = Booking::find($booking_id);
        $salon = $booking->booking_location->salon;
        $paypal_payment = SalonPaymentOptions::where('salon_id', $salon->id)->where('payment_gateway', 'paypal')->first();

        $payment_id = Session::get('paypal_payment_id');

        Session::forget('paypal_payment_id');

        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            return redirect()->back()->with('error_message', trans('salon.payment_failed'));
        }

        $paypal_conf = [
            'client_id' => $paypal_payment->public_key,
            'secret' => $paypal_payment->private_key,
            'settings' => [
                'mode' => env('PAYPAL_MODE','sandbox'),
                'http.ConnectionTimeOut' => 30,
                'log.LogEnabled' => true,
                'log.FileName' => storage_path() . '/logs/paypal.log',
                'log.LogLevel' => 'ERROR'
            ],
        ];

        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_payment->public_key,
                $paypal_payment->private_key)
        );
        $this->_api_context->setConfig($paypal_conf['settings']);

        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') {

            $payment_record = $this->payment_repo->storePayPalPaymentRecord($payment,$booking);

            if($payment_record['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.payment_success'));
            }

            return redirect()->back()->with('error_message', trans($payment_record['message']));
        }

        return redirect()->back()->with('error_message', trans('salon.payment_failed'));
    }

    public function payWithStripe(Request $request) {

        try {

            $charging_amount = $this->payment_repo->getChargingAmount($request->booking_id);
            $booking = Booking::find($request->booking_id);
            $salon = $booking->booking_location->salon;
            $stripe_payment = SalonPaymentOptions::where('salon_id', $salon->id)->where('payment_gateway', 'stripe')->first();

            if($charging_amount['status'] === 1) {

                $price_dollars = $charging_amount['charge_amount'];
                $price_cents = $price_dollars * 100;

                \Stripe\Stripe::setApiKey($stripe_payment->private_key);

                $token = $request->stripeToken;

                $charge = \Stripe\Charge::create([
                    'amount' => $price_cents,
                    'currency' => 'usd',
                    'description' => 'Saloni marketing payment',
                    'source' => $token,
                ]);

                $payment_record = $this->payment_repo->storeStripePaymentRecord($charge, $request->booking_id);

                if($payment_record['status'] === 1) {
                    return redirect()->back()->with('success_message', trans('salon.payment_success'));
                }

                return redirect()->back()->with('error_message', trans($payment_record['message']));

            }

            return redirect()->back()->with('error_message', trans('salon.payment_failed'));

        } catch (Exception $exc) {
            return redirect()->back()->with('error_message', $exc->getMessage());
        }

    }

    public function paypalRefund($sale_id) {
        //get amount to be refunded from db
        $amt = new Amount();
        $amt->setCurrency('USD')
            ->setTotal(0.01);

        $refundRequest = new RefundRequest();
        $refundRequest->setAmount($amt);

        $sale = new Sale();
        $sale->setId($sale_id);

        try {
            //get client id and client secret
            $apiContext = getApiContext($clientId, $clientSecret);
            $refundedSale = $sale->refundSale($refundRequest, $apiContext);

        } catch (Exception $exc) {
            return redirect()->back()->with('error_message', $exc->getMessage());
        }

        return redirect()->back()->with('success_message', trans('salon.refund_successful'));

    }

    public function stripeRefund($sale_id) {
        //get stripe api key
        \Stripe\Stripe::setApiKey("sk_test_fXIce05BYPc440wQPtcGu76B");

        //create refund request -> can refund partial amount or full amount
        $refund = \Stripe\Refund::create([
            'charge' => 'ch_mOlKDr0fZyD4SlbmvFN2',
            'amount' => 1000,
        ]);
    }

    public function getPaymentOptions() {

        $payment_options = $this->payment_repo->getPaymentOptions();

        if($payment_options['status'] === 1) {
            return ['status' => 1, 'payment_options' => $payment_options['payment_options']];
        }

        return ['status' => 0, 'message' => $payment_options['message']];

    }

    public function updateSalonPayment(Request $request) {

        $salon_payment = $this->payment_repo->updateSalonPayment($request->all());

        return ['status' => $salon_payment['status'], 'message' => $salon_payment['message']];

    }

    public function getBookingPayment($id) {

        $payment_info = $this->payment_repo->getPaymentInfo($id);
        $payment_options = $this->payment_repo->getBookingPaymentOptions($id);

        if($payment_info['status'] === 1 && $payment_options['status'] === 1) {
            return ['status' => 1, 'payment_info' => $payment_info['booking_list'], 'payment_options' => $payment_options['payment_options']];
        }

        return ['status' => 0, 'message' => $payment_info['message']];

    }

    public function getWsPayResponse(Request $request) {

        if($request->Success != 0) {
            $payment_record = $this->payment_repo->addWsPayPaymentRecord($request->all());

            if($payment_record['status'] === 1) {
                return redirect()->route('clientAppointments')->with('success_message', trans('salon.payment_complete'));
            }

            return redirect()->route('clientAppointments')->with('error_message', $payment_record['message']);

        } else {
            return redirect()->route('clientAppointments')->with('error_message', $request->ErrorMessage);
        }
    }

    public function getWsPayGateway($booking_id) {
        $payment_data = $this->payment_repo->getPaymentData($booking_id);

        if($payment_data['status'] === 1) {
            return view('salon.paymentRedirect', ['data' => $payment_data['data'], 'url' => $payment_data['url']]);
        }

        return redirect()->back()->with('error_message', $payment_data['message']);
    }
}
