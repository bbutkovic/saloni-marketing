<?php

namespace App\Repositories;

use App\Models\Countries;
use App\Models\Salon\SalonPaymentOptions;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Booking\Booking,
    Booking\Clients,
    Location,
    Salon\PaymentOptions,
    Salon\PaymentRecordExtras,
    Salon\PaymentRecords,
    Salons
};
use Illuminate\Support\Facades\DB;

class PaymentRepository {

    public function getChargingAmount($booking_id) {

        try {

            $booking = Booking::find($booking_id);
            $location = $booking->booking_location;
            $salon = Salons::find($location->salon_id);

            $salon_currency = $salon->currency;

            $charge_amount = preg_replace('/[^0-9-.]+/', '', currency($booking->pricing->price, $salon_currency, 'USD'));

            return ['status' => 1, 'charge_amount' => $charge_amount];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function storePayPalPaymentRecord($payment, $booking) {

        try {

            $location = $booking->booking_location;
            $salon = Salons::find($location->salon_id);

            $transactions = $payment->getTransactions();
            $relatedResources = $transactions[0]->getRelatedResources();
            $sale = $relatedResources[0]->getSale();
            $saleId = $sale->getId();

            if(Auth::user()) {
                $user_id = Auth::user()->id;
                $client_id = Clients::where('location_id', $location->id)->where('user_id', $user_id)->first();
            } else {
                $user_id = null;
                $client_id = null;
            }

            $record = new PaymentRecords;
            $record->payment_method = 'paypal';
            $record->salon_id = $salon->id;
            $record->location_id = $location->id;
            $record->save();

            $extras = new PaymentRecordExtras;
            $extras->payment_id = $record->id;
            $extras->sale_id = $saleId;
            $extras->user_id = Auth::user() ? Auth::user()->id : null;
            $extras->client_id = $client_id;
            $extras->amount_charged = $booking->pricing->price;
            $extras->currency = $salon->currency;
            $extras->payment_for = 'booking';
            $extras->identifier = $booking->id;
            $extras->save();

            return ['status' => 1];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function storeStripePaymentRecord($payment, $booking) {

        try {

            $booking = Booking::find($booking);
            $location = $booking->booking_location;
            $salon = Salons::find($location->salon_id);

            if(Auth::user()) {
                $user_id = Auth::user()->id;
                $client_id = Clients::where('location_id', $location->id)->where('user_id', $user_id)->first();
            } else {
                $user_id = null;
                $client_id = null;
            }

            $record = new PaymentRecords;
            $record->payment_method = 'stripe';
            $record->salon_id = $salon->id;
            $record->location_id = $location->id;
            $record->save();

            $extras = new PaymentRecordExtras;
            $extras->payment_id = $record->id;
            $extras->sale_id = $payment->id;
            $extras->user_id = Auth::user() ? Auth::user()->id : null;
            $extras->client_id = $client_id;
            $extras->amount_charged = $booking->pricing->price;
            $extras->currency = $salon->currency;
            $extras->payment_for = 'booking';
            $extras->identifier = $booking->id;
            $extras->save();

            return ['status' => 1];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function addWsPayPaymentRecord($payment) {
        try {

            $booking = Booking::find($payment['ShoppingCartID']);

            $booking_list = Booking::where('type_id', $booking->type_id)->get();
            foreach($booking_list as $booking_single) {
                $booking_single->booking_details->status = 'status_paid';
                $booking_single->booking_details->save();
            }

            $location = $booking->booking_location;
            $salon = Salons::find($location->salon_id);

            if(Auth::user()) {
                $user_id = Auth::user()->id;
                $client_id = Clients::where('location_id', $location->id)->where('user_id', $user_id)->first();
            } else {
                $user_id = null;
                $client_id = null;
            }

            $payment_records = new PaymentRecords;
            $payment_records->payment_method = 'wspay';
            $payment_records->salon_id = $salon->id;
            $payment_records->location_id = $location->id;
            $payment_records->save();

            $extras = new PaymentRecordExtras;
            $extras->payment_id = $payment_records->id;
            $extras->sale_id = $payment['WsPayOrderId'];
            $extras->user_id = $user_id;
            $extras->client_id = $client_id;
            $extras->amount_charged = $payment['Amount'];
            $extras->currency = $salon->currency;
            $extras->payment_for = 'booking';
            $extras->identifier = $booking->id;
            $extras->save();

            return ['status' => 1];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

    public function getPaymentOptions() {

        try {

            $salon = Salons::find(Auth::user()->salon_id);
            $payment_options = PaymentOptions::where('country', $salon->country)->first();
            return ['status' => 1, 'payment_options' => $payment_options];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function getBookingPaymentOptions($booking_id) {
        try {

            $booking = Booking::find($booking_id);
            $location = $booking->booking_location;
            $salon = Salons::find($location->salon_id);
            $payment_options = SalonPaymentOptions::select('payment_gateway')->where('salon_id', $salon->id)->where('status', 1)->get();

            return ['status' => 1, 'payment_options' => $payment_options];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

    public function updateSalonPayment($data) {

        try {

            $salon = Salons::find(Auth::user()->salon_id);
            $salon->online_payments = $data['payments'];
            $salon->save();

            $paypal = SalonPaymentOptions::where('salon_id', $salon->id)->where('payment_gateway', 'paypal')->first();
            if($paypal === null) {
                $paypal = new SalonPaymentOptions;
            }
            $paypal->salon_id = $salon->id;
            $paypal->payment_gateway = 'paypal';
            $paypal->status = $data['paypal_status'];
            $paypal->public_key = $data['paypal_public'];
            $paypal->private_key = $data['paypal_private'];
            $paypal->save();

            $stripe = SalonPaymentOptions::where('salon_id', $salon->id)->where('payment_gateway', 'stripe')->first();
            if($stripe === null) {
                $stripe = new SalonPaymentOptions;
            }
            $stripe->salon_id = $salon->id;
            $stripe->payment_gateway = 'stripe';
            $stripe->status = $data['stripe_status'];
            $stripe->public_key = $data['stripe_public'];
            $stripe->private_key = $data['stripe_private'];
            $stripe->save();

            $wspay = SalonPaymentOptions::where('salon_id', $salon->id)->where('payment_gateway', 'wspay')->first();
            if($wspay === null) {
                $wspay = new SalonPaymentOptions;
            }
            $wspay->salon_id = $salon->id;
            $wspay->payment_gateway = 'wspay';
            $wspay->status = $data['wspay_status'];
            $wspay->public_key = $data['wspay_public'];
            $wspay->private_key = $data['wspay_private'];
            $wspay->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function getPaymentInfo($data) {

        try {
            $booking = Booking::find($data);
            $salon = Salons::find($booking->booking_location->salon_id);

            if($booking->type === 'multiple') {
                $service_list = [];
                $multy_bookings = Booking::where('type_id', $booking->type_id)->get();
                foreach($multy_bookings as $multy_booking) {
                    $service_list[] = $multy_booking->service->service_details->name;
                }

                $booking_list[] = [
                    'id' => $booking->id,
                    'service' => implode(', ', $service_list),
                    'price' => $booking->pricing->price,
                    'currency' => $salon->currency
                ];
            } else {
                $booking_list[] = [
                    'id' => $booking->id,
                    'service' => $booking->service->service_details->name,
                    'price' => $booking->pricing->price,
                    'currency' => $salon->currency
                ];
            }

            return ['status' => 1, 'booking_list' => $booking_list];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    //wspay
    public function getPaymentData($booking_id) {
        try {
            $booking = Booking::find($booking_id);
            $salon = Salons::find($booking->booking_location->salon_id);

            $wspay_credentials = SalonPaymentOptions::where('salon_id', $salon->id)->where('payment_gateway', 'wspay')->first();
            //set shopID and secret key
            $shopID = $wspay_credentials->public_key;
            $this->secretKey = $wspay_credentials->private_key;

            $prod = 'https://form.wspay.biz/Authorization.aspx';
            $demo = 'https://formtest.wspay.biz/Authorization.aspx';
            $url = $demo;

            $shoppingCartID = $booking->id;
            $totalAmount = number_format($booking->pricing->price, 0, '', '');

            $first_name = $booking->client->first_name;
            $last_name = $booking->client->last_name;
            $country_code = $salon->country;
            $phone = $booking->client->phone;
            $email = $booking->client->email;

            $country = Countries::select('country_local_name')->where('country_identifier', $country_code)->first();

            $streetAddress = $booking->client->address;
            $city = $booking->client->city;
            $postCode = $booking->client->zip;

            $this->allowedLanguages = ['HR','EN','DE','IT','FR','NL','HU','RU','SK','CZ','PL','PT','ES','SL'];

            $data = [];

            $data['ShopID'] = $shopID;
            $data['ShoppingCartID'] = $shoppingCartID;
            $data['TotalAmount'] = $this->formatPrice($totalAmount);

            $data['Signature'] = $this->calculateRedirectSignature(
                $data['ShopID'],
                $data['ShoppingCartID'],
                $data['TotalAmount']
            );

            $data['ReturnUrl'] = route('WSPayResponse');
            $data['CancelUrl'] = route('clientAppointments');
            $data['ReturnErrorURL'] = route('WSPayResponse');

            $data['Lang'] = $this->getShortLocaleCode();

            $data['CustomerFirstname'] = $this->replaceCroatianChars($first_name);
            $data['CustomerLastName'] = $this->replaceCroatianChars($last_name);
            $data['CustomerAddress'] = $this->replaceCroatianChars($streetAddress);
            $data['CustomerCity'] = $this->replaceCroatianChars($city);
            $data['CustomerZIP'] = $this->replaceCroatianChars($postCode);
            $data['CustomerCountry'] = $this->replaceCroatianChars($country->name);
            $data['CustomerPhone'] = $this->replaceCroatianChars($phone);
            $data['CustomerEmail'] = $this->replaceCroatianChars($email);

            return ['status' => 1, 'data' => $data, 'url' => $url];
        }
        catch (Exception $exc)
        {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

    private function calculateRedirectSignature($shopId, $shoppingCartId, $totalAmount)
    {
        $cleanTotalAmount = str_replace(',', '', $totalAmount);
        $signature = $shopId.$this->secretKey.$shoppingCartId.$this->secretKey.$cleanTotalAmount.$this->secretKey;

        $signature = md5($signature);

        return $signature;
    }

    private function formatPrice($price)
    {
        $pricef = floatval($price);
        $result = number_format($pricef, 2 , ',' , '');

        return $result;
    }

    private function replaceCroatianChars($text)
    {
        $hr_chars = ['č', 'ć', 'đ', 'š', 'ž', 'Č', 'Ć', 'Đ', 'Š', 'Ž'];
        $ascii_chars = ['c', 'c', 'dj', 's', 'z', 'C', 'C', 'Dj', 'S', 'Z'];
        $result = str_replace($hr_chars, $ascii_chars, $text);

        return $result;
    }

    private function getShortLocaleCode()
    {
        $storeLocale = App::getLocale();

        $storeLocale = strtoupper(substr($storeLocale, 0, 2));

        if (!in_array($storeLocale, $this->allowedLanguages))
        {
            $storeLocale = 'EN';
        }

        return $storeLocale;
    }

}