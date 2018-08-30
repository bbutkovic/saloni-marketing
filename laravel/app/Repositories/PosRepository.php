<?php

namespace App\Repositories;

use App\Models\Booking\Booking;
use App\Models\Location;
use App\Models\Payments\ChargingDevice;
use App\Models\Payments\FiskalSettings;
use App\Models\Payments\StatsDate;
use App\Models\Salon\LoyaltyDiscounts;
use App\Models\Salon\PaymentRecordExtras;
use App\Models\Salon\PaymentRecords;
use App\Models\Salon\Service;
use App\Models\Salon\Vouchers;
use App\Models\Salons;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PosRepository {

    public function changeStatsDate($data) {
        try {

            $location = Location::find(Auth::user()->location_id);
            $stats_date = $location->stats_date;
            if($stats_date === null) {
                $stats_date = new StatsDate;
            }
            $stats_date->location_id = $location->id;
            $stats_date->start_date = date('Y-m-d', strtotime($data['start']));
            $stats_date->end_date = date('Y-m-d', strtotime($data['end']));
            $stats_date->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function getStatsDate($location) {
        try {
            $stats_date = $location->stats_date;

            if($stats_date === null) {
                $year = date('Y');
                $next_year = date('Y', strtotime('+1 year'));
                $stats_date = [
                    'start_date' => date('Y-m-d', strtotime($year.'-01-01')),
                    'end_date' => date('Y-m-d', strtotime($next_year.'-01-01'))
                ];
            }

            return ['status' => 1, 'starts_date' => $stats_date];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

    public function updateFiskalSettings($data) {

        try {
            $path = storage_path() . '/app/public/certificates';
            $settings = FiskalSettings::where('salon_id', Auth::user()->salon_id)->first();
            if($settings === null) {
                $settings = new FiskalSettings;
            } else {
                \Illuminate\Support\Facades\File::delete($path . '/' . $settings->certificate_name);
            }

            $name = substr(md5(rand()), 0, 30);
            $ext = $data['fiskalCertificate']->getClientOriginalExtension();
            $data['fiskalCertificate']->move($path, $name . '.'. $ext);

            $settings->salon_id = Auth::user()->salon_id;
            $settings->certificate_path = $path.'/'.$name;
            $settings->certificate_name = $name;
            $settings->password = $data['password'];
            $settings->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function addChargingDevice($device) {
        try {
            $location = Location::find(Auth::user()->location_id);
            if (isset($device['id']) && !empty($device['id'])) {
                $charging_device = ChargingDevice::find($device['id']);
            } else {
                $charging_device = new ChargingDevice;
            }
            $charging_device->location_id = $location->id;
            $charging_device->device_label = $device['label'];
            $charging_device->location_label = $location->billing_info->location_label;
            $charging_device->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly'), 'device' => $charging_device];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

    public function deleteChargingDevice($id) {
        try {
            $device = ChargingDevice::find($id);
            if($device != null) {
                $device->delete();
            }
            return ['status' => 1, 'message' => trans('salon.deleted_successfully')];
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => trans('salon.delete_failed')];
        }
    }

    public function createInvoice($data) {
        try {

            $booking = Booking::find($data['id']);
            $location = $booking->booking_location;
            $salon = Salons::find($location->salon_id);

            $payment_record = new PaymentRecords;
            $payment_record->payment_method = 'cash';
            $payment_record->salon_id = $salon->id;
            $payment_record->location_id = $location->id;
            $payment_record->save();

            $record_extras = new PaymentRecordExtras;
            $record_extras->payment_id = $payment_record->id;
            $record_extras->sale_id = 'cash';
            $record_extras->user_id = null;
            $record_extras->client_id = $booking->client->id;
            $record_extras->amount_charged = $booking->pricing->price;
            $record_extras->currency = $salon->currency;
            $record_extras->payment_for = 'booking';
            $record_extras->selected_services = implode(',', $data['services']);
            $record_extras->identifier = $booking->id;
            $record_extras->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function getInvoices($amount) {
        try {
            $invoice_list = [];
            $discount = 0;
            if($amount === 0) {
                $invoices = PaymentRecords::where('location_id', Auth::user()->location_id)->get();
            } else {
                $invoices = PaymentRecords::where('id', $amount)->get();
            }

            foreach($invoices as $invoice) {
                $total_price_no_vat = 0;
                $total_price = 0;
                $service_list = [];
                $booking = Booking::find($invoice->payment_extras->identifier);

                $pricing = $booking->pricing;
                if($pricing->code_used != null) {
                    $voucher = Vouchers::where('code', $pricing->code_used)->first();
                    $voucher_discount = $voucher->discount . '%';
                } else {
                    $voucher_discount = 0;
                }

                if($pricing->selected_discount != null) {
                    $discount = 1;
                    $discount_amount = LoyaltyDiscounts::find($pricing->selected_discount)->discount;
                } else {
                    $discount = 0;
                    $discount_amount = 0;
                }

                if($pricing->free_service != null) {
                    $discount = '100%';
                }

                $services = explode(',', $invoice->payment_extras->selected_services);
                foreach($services as $service) {
                    $booking_single = Booking::find($service);
                    $srv = $booking_single->service;

                    if($pricing->free_service != null && $pricing->free_service == $srv->id) {
                        $service_discounted = 1;
                        $price_no_vat = 0;
                        $price_vat = 0;
                    } else {
                        $service_discounted = 0;
                        $price_no_vat = $srv->service_details->price_no_vat;
                        $price_vat = $srv->service_details->base_price;
                    }

                    $service_list[] = [
                        'id' => $srv->id,
                        'name' => $srv->service_details->name,
                        'price_no_vat' => $srv->service_details->price_no_vat,
                        'vat' => $srv->service_details->vat,
                        'total_price' => $srv->service_details->base_price . ' ' . $invoice->payment_extras->currency,
                        'service_discounted' => $service_discounted,
                        'code' => $srv->service_details->code
                    ];

                    $total_price_no_vat += $price_no_vat;
                    $total_price += $price_vat;
                }
                $invoice_list[] = [
                    'location' => $booking->booking_location,
                    'billing_info' => $booking->booking_location->billing_info,
                    'invoice_id' => $invoice->id,
                    'invoice_date' => date('d M Y H:i', strtotime($invoice->created_at)),
                    'payment_for' => $invoice->payment_extras->payment_for,
                    'booking_id' => $invoice->payment_extras->identifier,
                    'client' => [
                        'id' => $booking->client->id,
                        'name' => $booking->client->first_name . ' ' . $booking->client->last_name,
                        'address' => $booking->client->address . ' ' . $booking->client->city . ' ' . $booking->client->zip,
                        'city' => $booking->client->city,
                        'zip' => $booking->client->zip,
                        'phone' => $booking->client->phone
                    ],
                    'services' => $service_list,
                    'amount_charged' => $invoice->payment_extras->amount_charged . ' ' . strtoupper($invoice->payment_extras->currency),
                    'currency' => $invoice->payment_extras->currency,
                    'paid_with' => $invoice->payment_method,
                    'total_no_vat' => $total_price_no_vat . ' ' . strtoupper($invoice->payment_extras->currency),
                    'voucher_used' => $voucher_discount,
                    'applied_discount' => $discount,
                    'discount_amount' => $discount_amount,
                    'price_w_vat' => $total_price . ' ' . strtoupper($invoice->payment_extras->currency)
                ];
            }

            return ['status' => 1, 'invoice_list' => $invoice_list];
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

}