<?php

namespace App\Http\Controllers;

use App\Models\Countries;
use App\Models\Location;
use App\Models\Payments\ChargingDevice;
use App\Models\Salons;
use App\Repositories\{PosRepository,PaymentRepository};
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function __construct() {
        $this->pos_repo = new PosRepository;
        $this->payment_repo = new PaymentRepository;
    }

    public function getBillingInfo() {

        $location = Location::find(Auth::user()->location_id);
        $country_list = Countries::all();

        return view('pos.billingInfo', ['location' => $location, 'countries' => $country_list]);
    }

    public function getSettings() {

        $location = Location::find(Auth::user()->location_id);
        $salon = $location->salon;

        return view('pos.settings', ['salon' => $salon, 'location' => $location]);
    }

    public function updateFiskalSettings(Request $request) {

        $pos_settings = $this->pos_repo->updateFiskalSettings($request->all());

        if($pos_settings['status'] === 1) {
            return redirect()->back()->with('success_message', $pos_settings['message']);
        }

        return redirect()->back()->with('error_message', $pos_settings['message']);
    }

    public function getChargingDevices() {

        $location = Location::find(Auth::user()->location_id);
        $charging_devices = ChargingDevice::where('location_id', $location->id)->get();

        return view('pos.chargingDevices', ['location' => $location, 'charging_devices' => $charging_devices]);
    }

    public function addChargingDevice(Request $request) {

        $device = $this->pos_repo->addChargingDevice($request->all());

        if($device['status'] === 1) {
            return ['status' => 1, 'message' => $device['message'], 'device' => $device['device']];
        }
        return ['status' => 0, 'message' => $device['message']];
    }

    public function deleteChargingDevice(Request $request) {
        $device = $this->pos_repo->deleteChargingDevice($request->id);

        return ['status' => $device['status'], 'message' => $device['message']];
    }

    public function createInvoice(Request $request) {

        $invoice = $this->pos_repo->createInvoice($request->all());

        return ['status' => $invoice['status'], 'message' => $invoice['message']];

    }

    public function getInvoices() {

        $invoices = $this->pos_repo->getInvoices(0);

        if($invoices['status'] === 1) {
            return view('pos.invoices', ['invoices' => $invoices['invoice_list']]);
        }

        return redirect()->back()->with('error_message', $invoices['message']);
    }

    public function createInvoicePDF($id) {
        try {
            $data = $this->getInvoice($id);

            if($data['status'] === 1) {
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadView('partials.invoicePDF', ['data' => $data['invoice']]);
                return $pdf->stream();
            }

            return redirect()->back()->with('error_message', $data['message']);
        } catch (Exception $exc) {
            return redirect()->back()->with('error_message', $exc->getMessage());
        }

    }

    public function getInvoice($id) {

        $invoice = $this->pos_repo->getInvoices($id);

        if($invoice['status'] === 1) {
            return ['status' => 1, 'invoice' => $invoice['invoice_list'][0]];
        }
        return ['status' => 0, 'message' => $invoice['message']];
    }

}
