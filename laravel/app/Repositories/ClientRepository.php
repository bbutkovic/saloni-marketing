<?php

namespace App\Repositories;

use App\Models\Booking\Booking;
use App\Models\Location;
use App\Models\Salon\LoyaltyDiscounts;
use App\Models\Salon\PaymentRecordExtras;
use App\Models\Salon\SalonPaymentOptions;
use App\Models\Salons;
use App\Models\Users\PrivacySettings;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking\Clients;
use App\Models\Salon\{Service,LoyaltyPrograms};
use App\Models\Clients\{ClientSettings,ClientLabels,ClientReferrals,ClientFields};
use App\Role;
use DB;

class ClientRepository {

    public function updateClientSettings($data) {
        
        try {

            $salon = Salons::find(Auth::user()->salon_id);
            
            $client_settings = ClientSettings::where('salon_id', $salon->id)->first();
            
            $client_settings->sms = isset($data['sms']) ? 1 : 0;
            $client_settings->email = isset($data['email']) ? 1 : 0;
            $client_settings->viber = isset($data['viber']) ? 1 : 0;
            $client_settings->facebook = isset($data['facebook']) ? 1 : 0;
            if($data['name_format'] == 1) {
                $name_format = 'first_last';
            } else {
                $name_format = 'last_first';
            }
            $client_settings->name_format = $name_format;
            $client_settings->save();
            
            $client_fields = ClientFields::where('salon_id', $salon->id)->first();
            if($client_fields === null) {
                $client_fields = new ClientFields;
            }
            $client_fields->salon_id = $salon->id;
            $client_fields->first_name = 1;
            $client_fields->last_name = 1;
            $client_fields->phone = isset($data['phone']) ? 1 : 0;
            $client_fields->email = 1;
            $client_fields->address = isset($data['address']) ? 1 : 0;
            $client_fields->gender = isset($data['gender']) ? 1 : 0;
            $client_fields->save();
            
            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
            
                
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function saveClientLabel($data) {

        try {

            $salon = Salons::find(Auth::user()->salon_id);

            if($data['id'] != null) {
                $client_label = ClientLabels::find($data['id']);
            } else {
                $client_label = new ClientLabels;
            }
            $client_label->salon_id = $salon->id;
            $client_label->name = $data['label'];
            $client_label->color = $data['color'];
            $client_label->save();

            return ['status' => 1, 'label' => $client_label, 'message' => trans('salon.updated_successfuly')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }
    
    public function saveClientReferral($data) {
        try {

            $salon = Salons::find(Auth::user()->salon_id);

            if($data['id'] != null) {
                $client_referral = ClientReferrals::find($data['id']);
            } else {
                $client_referral = new ClientReferrals;
            }
            $client_referral->salon_id = $salon->id;
            $client_referral->name = $data['referral'];
            $client_referral->save();

            return ['status' => 1, 'referral' => $client_referral, 'message' => trans('salon.updated_successfuly')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }


    }
    
    public function updateClientInfo($data) {
        
        try {

            $client = Clients::find($data['client_id']);
            
            if(isset($data['email'])) {
                
                $check_email = Clients::where('location_id', Auth::user()->location_id)->where('email', $data['email'])->where('id', '!=', $client->id)->first();
                
                if($check_email != null) {
                    return ['status' => 0, 'message' => trans('salon.email_address_taken')];
                }
                
            }
            
            $client->first_name = isset($data['first_name']) ? $data['first_name'] : null;
            $client->last_name = isset($data['last_name']) ? $data['last_name'] : null;
            $client->email = isset($data['email']) ? $data['email'] : null;
            $client->phone = isset($data['phone']) ? $data['phone'] : null;
            $client->address = isset($data['address']) ? $data['address'] : null;
            $client->city = isset($data['city']) ? $data['city'] : null;
            $client->zip = isset($data['zip']) ? $data['zip'] : null;
            $client->gender = isset($data['gender']) ? $data['gender'] : null;
            $client->birthday = isset($data['birthday']) ? $data['birthday'] : null;
            $client->sms_reminders = $data['allow_sms_reminders'];
            $client->sms_marketing = $data['allow_sms_marketing'];
            $client->email_reminders = $data['allow_email_reminders'];
            $client->email_marketing = $data['allow_email_marketing'];
            $client->viber_reminders = $data['allow_viber_reminders'];
            $client->viber_marketing = $data['allow_viber_marketing'];
            $client->facebook_reminders = $data['allow_facebook_reminders'];
            $client->facebook_marketing = $data['allow_facebook_marketing'];
            $client->save();

            foreach($data['custom_fields'] as $key=>$custom_field) {
                $field_name = $custom_field['fieldName'];
                $field_value = $custom_field['fieldValue'];

                if( $field_name=== 'custom_field_1') {
                    $client->custom_field_1 = $field_value;
                } else if ($field_name === 'custom_field_2') {
                    $client->custom_field_2 = $field_value;
                } else if ($field_name === 'custom_field_3') {
                    $client->custom_field_3 = $field_value;
                } else if ($field_name === 'custom_field_4') {
                    $client->custom_field_4 = $field_value;
                }
            }
            $client->save();
            
            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }

    public function checkLoyaltyProgram($user, $location) {

        $program = LoyaltyPrograms::where('location_id', $location->id)->first();
        $salon = Salons::find($location->salon_id);
        $currency = $salon->currency;

        $client = Clients::where('location_id', $location->id)->where('user_id', $user->id)->first();

        if($program != null) {
            if ($program->loyalty_type === 0) {
                return ['type' => 0];
            } else if ($program->loyalty_type === 1) {
                $max_amount = $program->max_amount;
                $message = trans('salon.free_booking', ['max_amount' => $max_amount, 'currency' => $currency]);
                if ($client != null && $client->arrival_points >= $location->loyalty_program->required_arrivals) {
                    if($client != null) {
                        $points_needed = $location->loyalty_program->required_arrivals - $client->arrival_points;
                    } else {
                        $points_needed = $location->loyalty_program->required_arrivals;
                    }
                    return ['status' => 1, 'type' => 1, 'points' => $client->arrival_points, 'points_needed' => $points_needed, 'max_amount' => $max_amount, 'message' => $message];
                } else {
                    if($client != null) {
                        $points_needed = $location->loyalty_program->required_arrivals - $client->arrival_points;
                    } else {
                        $points_needed = $location->loyalty_program->required_arrivals;
                    }
                    return ['status' => 0, 'type' => 1, 'points' => 0, 'points_needed' => $points_needed, 'max_amount' => $max_amount, 'message' => $message];
                }
            } else if ($program->loyalty_type === 2) {
                $loyalty_repo = new LoyaltyRepository;
                $groups = $loyalty_repo->getFreeGroupsInfo($program, $location);
                $message = trans('salon.select_free_service');
                if ($client != null && $client->arrival_points >= $location->loyalty_program->required_arrivals) {
                    return ['status' => 1, 'type' => 2, 'points' => $client->arrival_points, 'free_groups' => $groups, 'points_needed' => 0, 'message' => $message];
                } else {
                    if($client != null) {
                        $points_needed = $location->loyalty_program->required_arrivals - $client->arrival_points;
                    } else {
                        $points_needed = $location->loyalty_program->required_arrivals;
                    }
                    return ['status' => 0, 'type' => 2, 'points' => 0, 'free_groups' => $groups, 'points_needed' => $points_needed, 'message' => $message];
                }
            } else if ($program->loyalty_type === 3) {

                $client_points = $client->loyalty_points ?? 0;
                $message = trans('salon.booking_discount');
                $all_discounts = LoyaltyDiscounts::where('salon_id', $location->salon_id)->get();

                if ($client != null && $client_points != 0) {
                    $available_discounts = LoyaltyDiscounts::where('salon_id', $location->salon_id)->where('points', '<=', $client_points)->get();
                    if ($all_discounts->isNotEmpty() && $available_discounts->isNotEmpty()) {
                        return ['status' => 1, 'type' => 3, 'points' => $client_points, 'message' => $message, 'all_discounts' => $all_discounts, 'available_discounts' => $available_discounts, 'points_needed' => 0];
                    } else {
                        $first_discount = LoyaltyDiscounts::where('salon_id', $location->salon_id)->orderBy('points', 'ASC')->first();
                        $points_needed = $first_discount->points - $client_points;
                        return ['status' => 0, 'type' => 3, 'points' => $client_points, 'message' => $message, 'all_discounts' => $all_discounts, 'points_needed' => $points_needed];
                    }
                } else {
                    $first_discount = LoyaltyDiscounts::where('salon_id', $location->salon_id)->orderBy('points', 'ASC')->first();
                    $points_needed = $first_discount->points - $client_points;
                    return ['status' => 0, 'type' => 3, 'points' => $client_points, 'message' => $message, 'all_discounts' => $all_discounts, 'points_needed' => $points_needed];
                }
            }
        } else {
            return ['status' => 0];
        }
    }

    public function getLoyaltyPoints($user,$location) {

        $client = Clients::where('user_id', $user->id)->where('location_id', $location->id)->first();

        if($client != null) {
            $points = $client->loyalty_points;
            return $points;
        }

    }

    public function checkIfClientExists($data) {

        $client = Clients::where('location_id', $data['location'])->where('email', $data['email'])->first();
        if($client != null) {
            return ['status' => 0];
        }

        return ['status' => 1];
    }

    public function getClientAppointments($user) {

        try {
            $client_list = Clients::where('user_id', $user->id)->get();
            $booking_list = [];

            foreach($client_list as $client) {
                $location_booking = Booking::where('client_id', $client->id)->groupBy('type_id')->orderBy('id', 'DESC')->get();

                foreach($location_booking as $booking) {
                    $salon = Salons::find($booking->booking_location->salon_id);
                    $current_date = new \DateTime("now", new \DateTimeZone($salon->time_zone));
                    $cancel_time = $salon->booking_policy->cancel_reschedule_time;
                    $check_date = date('Y-m-d H:i', strtotime($booking->booking_date . ' ' .$booking->start));
                    $cancel_time = date('Y-m-d H:i', strtotime('+'.$cancel_time.' hour', strtotime($current_date->format('Y-m-d H:i'))));
                    $status = $booking->booking_details->status;
                    $salon_payments = SalonPaymentOptions::where('salon_id', $salon->id)->where('payment_gateway', 'stripe')->first();

                    if($salon_payments != null) {
                        $stripe = $salon_payments->public_key;
                    } else {
                        $stripe = null;
                    }

                    if($cancel_time < $check_date && ($status != 'status_cancelled' || $status != 'status_complete')) {
                        $options = 1;
                    } else {
                        $options = 0;
                    }
                    $payment = PaymentRecordExtras::where('identifier', $booking->id)->first();

                    if($payment != null) {
                        $payment = 1;
                    } else {
                        $payment = 0;
                    }

                    if($booking->type === 'multiple') {
                        $service_list = [];
                        $multy_bookings = Booking::where('type_id', $booking->type_id)->get();
                        foreach($multy_bookings as $multy_booking) {
                            $service_list[] = $multy_booking->service->service_details->name;
                            $end = $multy_booking->booking_end;
                        }
                        $booking_list[] = [
                            'id' => $booking->id,
                            'type' => $booking->type,
                            'service' => implode(', ', $service_list),
                            'price' => $booking->pricing->price . ' ' . $salon->currency,
                            'salon' => $salon,
                            'online_payments' => $salon->online_payments,
                            'location' => $booking->booking_location->location_name,
                            'date' => $booking->getDateAttribute(),
                            'start' => $booking->getStartTimeAttribute(),
                            'end' => date('H:i', strtotime($end)),
                            'status' => $status,
                            'payment_status' => $payment,
                            'options' => $options,
                            'stripe' => $stripe
                        ];
                    } else {
                        $booking_list[] = [
                            'id' => $booking->id,
                            'type' => $booking->type,
                            'service' => $booking->service->service_details->name,
                            'price' => $booking->pricing->price,
                            'salon' => $salon,
                            'online_payments' => $salon->online_payments,
                            'location' => $booking->booking_location->location_name,
                            'date' => $booking->getDateAttribute(),
                            'start' => $booking->getStartTimeAttribute(),
                            'end' => $booking->getEndTimeAttribute(),
                            'status' => $booking->booking_details->status,
                            'payment_status' => $payment,
                            'options' => $options,
                            'stripe' => $stripe
                        ];
                    }
                }
            }

            return ['status' => 1, 'booking_list' => $booking_list];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function getUpcomingBookings($data) {

        try {

            $user = Auth::user();
            $upcoming_bookings = [];

            $bookings = $this->getClientAppointments($user);

            if ($bookings['status'] === 1) {
                foreach ($bookings['booking_list'] as $booking) {
                    $booking_time = date('Y-m-d H:i:s', strtotime($booking['date'] . ' ' . $booking['start']));
                    $time_check = date('Y-m-d H:i:s', strtotime($data['date'] . ' ' . $data['time']));

                    if ($booking_time > $time_check) {
                        $upcoming_bookings[] = [
                          'date' => $booking['date'],
                          'time' => $booking['start'] . ' - ' . $booking['end'],
                          'service' => $booking['service'],
                          'location' => $booking['location']
                        ];
                    }

                }
            }

            return ['status' => 1, 'booking_list' => $upcoming_bookings];

        } catch(Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function getAppointmentStats($appointments) {

        $stats = [];
        $money_spent = 0;
        $appointments_made = 0;
        $appointments_complete = 0;

        foreach($appointments as $appointment) {
            $money_spent += $appointment['price'];
            $appointments_made ++;
            if($appointment['status'] === 'status_complete') {
                $appointments_complete++;
            }
        }

        $stats[] = [
            'money_spent' => $money_spent,
            'appointments_made' => $appointments_made,
            'appointments_complete' => $appointments_complete
        ];

        return $stats;

    }

    public function getNewClients() {
        try {

            $new_clients = [];
            $male = 0;
            $female = 0;
            $undefined = 0;

            $clients = Clients::where('location_id', Auth::user()->location_id)
                ->whereRaw('year(`created_at`) = ? && month(`created_at`) = ?', array(date('Y'), date('m')))
                ->get();

            foreach($clients as $client) {
                if($client->gender === 1) {
                    $male++;
                } else if($client->gender === 2) {
                    $female++;
                } else {
                    $undefined++;
                }
            }

            $new_clients[] = [
                'male' => $male,
                'female' => $female,
                'undefined' => $undefined
            ];

            return ['status' => 1, 'clients' => $new_clients];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

    public function getClientSelection($location, $clients_inactive, $clients_gender, $older_than, $younger_than, $with_label, $with_referral, $with_staff, $with_category, $with_service, $loyalty_points) {

        try {

            $clients = Clients::where('location_id', $location->id)->get();
            $client_list = [];

            foreach($clients as $client) {

                $client_list[] = $client;

            }

            if($clients_inactive != 0) {
                foreach($client_list as $key=>$client_inactive) {
                    $last_booking = Booking::where('client_id', $client_inactive['id'])->orderBy('booking_date', 'desc')->first();
                    $check_date = date('Y-m-d', strtotime('+'.$clients_inactive.' month', strtotime($last_booking['booking_date'])));

                    if($check_date < date('Y-m-d')) {
                        unset($client_list[$key]);
                    }
                }
            }

            if($older_than != null && $younger_than != null) {
                foreach($client_list as $key=>$client_age) {
                    $date = new \DateTime($client_age['birthday']);
                    $now = new \DateTime();
                    $age = $now->diff($date)->y;
                    if($younger_than = 90) {
                        $younger_than = 200;
                    }
                    if($age > $younger_than || $age < $older_than) {
                        unset($client_list[$key]);
                    }
                }
            }

            if($clients_gender != 0) {
                foreach($client_list as $key=>$client_gender) {
                    if($client_gender['gender'] != $clients_gender) {
                        unset($client_list[$key]);
                    }
                }
            }

            if($with_label != 0) {
                foreach($client_list as $key=>$client_label) {
                    if($client_label['label'] != $with_label) {
                        unset($client_list[$key]);
                    }
                }
            }

            if($with_referral != 0) {
                foreach($client_list as $key=>$client_referral) {
                    if($client_referral['referral'] != $with_referral) {
                        unset($client_list[$key]);
                    }
                }
            }

            if($with_staff != 0) {
                $staff_list = explode(',', $with_staff);
                foreach($client_list as $key=>$client_staff) {
                    $client_bookings = Booking::where('client_id', $client_staff->id)->where('booking_date', '>', date('Y-m-d'))->get();
                    $check = 0;
                    foreach($client_bookings as $booking_staff) {
                        if(!in_array($booking_staff->staff->id, $staff_list)) {
                            $check = 0;
                        } else {
                            $check = 1;
                            break;
                        }
                    }
                    if($check != 1) {
                        unset($client_list[$key]);
                    }
                }
            }

            if($with_category != 0) {
                $cat_list = explode(',', $with_category);
                foreach($client_list as $key=>$client_cat) {
                    $client_bookings = Booking::where('client_id', $client_cat->id)->where('booking_date', '>', date('Y-m-d'))->get();
                    $check = 0;
                    foreach($client_bookings as $booking_cat) {
                        if(!in_array($booking_cat->service->service_category->id, $cat_list)) {
                            $check = 0;
                        } else {
                            $check = 1;
                            break;
                        }
                    }
                    if($check != 1) {
                        unset($client_list[$key]);
                    }
                }
            }

            if($with_service != 0) {
                $service_list = explode(',', $with_service);
                foreach($client_list as $key=>$client_service) {
                    $client_bookings = Booking::where('client_id', $client_service->id)->where('booking_date', '>', date('Y-m-d'))->get();
                    $check = 0;
                    foreach($client_bookings as $booking_service) {
                        if(!in_array($booking_service->service->id, $service_list)) {
                            $check = 0;
                        } else {
                            $check = 1;
                            break;
                        }
                    }
                    if($check != 1) {
                        unset($client_list[$key]);
                    }
                }
            }


            if($loyalty_points != null) {

                foreach($client_list as $key=>$client_points) {
                    if($client_points->loyalty_points < $loyalty_points) {
                        unset($client_list[$key]);
                    }
                }
            }

            return ['status' => 1, 'clients' => $client_list];

        } catch (Exception $exc) {
            return ['status' => 0, $exc->getMessage()];
        }

    }

    public function updatePrivacySettings($data) {

        try {

            $privacy = PrivacySettings::where('user_id', Auth::user()->id)->first();
            if($privacy === null) {
                $privacy = new PrivacySettings;
            }
            $privacy->user_id = Auth::user()->id;
            $privacy->sms_reminder = $data['sms_reminders'];
            $privacy->sms_marketing = $data['sms_marketing'];
            $privacy->email_reminder = $data['email_reminders'];
            $privacy->email_marketing = $data['email_marketing'];
            $privacy->viber_reminder = $data['viber_reminders'];
            $privacy->viber_marketing = $data['viber_marketing'];
            $privacy->facebook_reminder = $data['facebook_reminders'];
            $privacy->facebook_marketing = $data['facebook_marketing'];
            $privacy->save();

            return ['status' => 1, 'message' => trans('salon.privacy_updated')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function getClientLoyalty() {

        try {
            $loyalty_status = [];
            $client_objects = Clients::where('user_id', Auth::user()->id)->get();

            foreach($client_objects as $client) {
                $location = Location::find($client->location_id);
                $loyalty_status[] = [
                    'salon' => Salons::find($location->salon_id),
                    'location' => $location,
                    'loyalty_points' => $client->loyalty_points,
                    'arrival_points' => $client->arrival_points
                ];
            }

            return ['status' => 1, 'loyalty' => $loyalty_status];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }



    }
    
}