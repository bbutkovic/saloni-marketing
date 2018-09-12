<?php

namespace App\Repositories;

use Illuminate\Support\Facades\{Auth,URL,Hash};
use App\Notifications\{
    BookingConfirmation, WaitingListNotification, NewClient
};

use App\Models\{
    Marketing\MarketingTemplate, Marketing\Reminders, Salons, Location, SalonService, LocalHours, Services, Languages
};
use App\Models\Salon\{ServiceStaff,Service,LoyaltyManagement,CustomFields,SelectOptions,Vouchers,LoyaltyPrograms,LoyaltyDiscounts};
use App\Models\Booking\{BookingPolicy,BookingFields,Booking,BookingDetails,Clients,CalendarOptions,BookingPrice,DiscountCodes,CalendarColors};
use App\Models\Clients\ClientLocations;
use App\Models\Users\UserExtras;
use App\User;
use DB,Session;

class BookingRepository {
    
    private $timetable_multiple = [];
    
    public function updateBookingPolicies($salon, $data) {

        try {

            $booking_policy = BookingPolicy::where('salon_id', $salon->id)->first();

            if($booking_policy === null) {
                $booking_policy = new BookingPolicy;
            }
            $booking_policy->salon_id = $salon->id;
            $booking_policy->staff_selection = $data['staff_selection'];
            $booking_policy->show_prices = $data['show_prices'];
            $booking_policy->first_name_only = $data['first_name_only'];
            $booking_policy->multiple_staff = $data['multiple_staff'];
            $booking_policy->cancel_reschedule_time = $data['cancel_limit'];
            $booking_policy->booking_slot = $data['booking_slot'];
            $booking_policy->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }
    
    public function updateFields($data) {
        
        try {
            if($salon = Auth::user()->salon) {
                $field = CustomFields::find($data['id']);
                $field->field_status = $data['state'];
                $field->save();
                
                return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
            }
            return ['status' => 0, 'message' => trans('salon.error_updating')];
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function addCustomFields($data) {
        
        try {
            if($salon = Auth::user()->salon) {
                $fields = [];
                foreach($data['field_name'] as $field) {
                    
                    $custom_status = BookingFields::where('salon_id', $salon->id)->max('custom_status');
                    $custom_status += 1;
                    
                    if ($custom_status > 3) {
                        return ['status' => 0, 'message' => trans('salon.max_fields_reached')];
                    }
                    
                    $custom_fields = new BookingFields;
                    $custom_fields->salon_id = $salon->id;
                    $custom_fields->field_name = 'custom_field_' . $custom_status;
                    $custom_fields->field_title = $field;
                    $custom_fields->field_status = 1;
                    $custom_fields->field_type = 1;
                    $custom_fields->custom_status = $custom_status;
                    $custom_fields->save();
                }
                
                return ['status' => 1];
                
            }
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function getServices($id) {
        
        $services = SalonService::where('location_id', $id)->get();

        $services_list = [];
        foreach($services as $service) {
            $services_list[] = Services::where('id', $service->service_id)->get();
        }

        if(count($services_list) > 0) {
            return ['status' => 1, 'services' => $services_list];
        }
        
        return ['status' => 0];
        
    }

    public function getBookingSchedule($location_id, $user, $date, $schedule, $services) {
        
        $timetable = [];

        $s_d = 0;
        foreach($services as $service) {
            $service_arr = explode('-', $service);
            $service_id = $service_arr[1];
            $time2 = date('H:i:s', strtotime(Service::find($service_id)->service_details->service_length));
            $s_d += strtotime($time2);
        }
        
        $service_duration_hours = date('H:i:s', $s_d);
        $time = explode(':', $service_duration_hours);
        $service_duration = ($time[0]*60) + ($time[1]) + ($time[2]/60);
        
        $location = Location::find($location_id);
        $salon = Salons::find($location->salon_id);
        $booking_policy = BookingPolicy::where('salon_id', $salon->id)->first();
        $date = date('Y-m-d', strtotime($date));
        
        foreach($schedule as $single_day) {

            if ($date === $single_day['date']['date']) {
                $day_start = $single_day['timetable']['start'];
                $day_end = $single_day['timetable']['end'];
                $lunch_start = date('H:i:s', strtotime($single_day['timetable']['lunch_start']));
                $lunch_end = date('H:i:s', strtotime($single_day['timetable']['lunch_end']));
                
                $start_day = date('H:i:s', strtotime($date . ' ' . $day_start));
                $end_day = date('H:i:s', strtotime($date . ' ' . $day_end));

                while ($start_day < $end_day) {
                    $start_itr = date('H:i:s', strtotime('+'.$service_duration.' minutes', strtotime($start_day)));
                    if($start_itr < $end_day) {

                        if($start_itr > $lunch_start && $start_day < $lunch_end) {
                            $start_day = date('H:i:s', strtotime('+5 minute', strtotime($start_day)));
                        } else {
                            $booking_day_end = date('H:i:s', strtotime('+'.$service_duration.' minutes', strtotime($start_day)));
    
                            
                            if($user != null) {
                                $count_bookings = Booking::where('staff_id', $user->id)->where('booking_date', $date)->count();
                                
                                $bookings = Booking::where('staff_id', $user->id)->where('booking_date', $date)
                                    ->whereRaw('(((? < start) AND (? <= start)) OR ((? >= booking_end) AND (? >= booking_end)))',
                                        array($start_day, $booking_day_end, $start_day, $booking_day_end))->count();
                            } else {
                                $count_bookings = Booking::where('location_id', $location_id)->where('booking_date', $date)->count();
                                
                                $bookings = Booking::where('location_id', $location_id)->where('booking_date', $date)
                                    ->whereRaw('(((? < start) AND (? <= start)) OR ((? >= booking_end) AND (? >= booking_end)))',
                                        array($start_day, $booking_day_end, $start_day, $booking_day_end))->count();
                            }
                            
                            if ($count_bookings != $bookings) {
                                $start_day = date('H:i:s', strtotime('+5 minute', strtotime($start_day)));
                                
                                continue;
                            } else {
                                $timetable[] = [
                                    'from' => date('H:i', strtotime($start_day)),
                                    'to' => date('H:i', strtotime('+'.$service_duration.' minute', strtotime($start_day)))
                                ];
                                
                                $start_day = date('H:i:s', strtotime('+'.$booking_policy->booking_slot.' minute', strtotime($start_day)));
    
                            }
                        }
                    } else {
                        $start_day = $end_day;
                    }
                }
            }
        }

        return $timetable;
        
    }
    
    public function addNewBooking($location, $data, $booking_options) {
        try {
            $salon = Salons::find($location->salon_id);
            $booking_date = date('Y-m-d', strtotime($data['booking_date']));
            $booking_list = Booking::where('location_id', $location->id)->where('booking_date', $booking_date)->get();
            $calendar_options = CalendarOptions::where('salon_id', $location->salon_id)->first();

            if($data['staff'][0] == null) {
                $staff_list = User::where('location_id', $location->id)->get();
                $min = 99999;
                $booking_staff = [];
                foreach($staff_list as $staff) {
                    $min_staff[$staff->id] = 0;
                    if($staff->user_extras->available_booking == 1) {
                        foreach($booking_list as $list) {
                            if($list->staff_id === $staff->id) {
                                $min_staff[$staff->id] += 1;
                            }
                        }
                        if($min > $min_staff[$staff->id]) {
                            $min = $min_staff[$staff->id];
                            $min_id = $staff->id;
                        }
                    }
                }
                $booking_staff[] = $min_id;
            } else {
                $booking_staff = $data['staff'];
            }
            
            DB::beginTransaction();
            
            $service_index = 0;
            $points = 0;
            $booking_start = $data['booking_from'];
            $type_id = base64_encode(random_bytes(7));

            foreach($data['service'] as $service) {
                //get service id from array
                $service_string = explode('-', $service);
                $service_id = $service_string[1];
                $service = Service::find($service_id);

                //check if service generates points
                if(isset($location->loyalty_program) && $location->loyalty_program->loyalty_type === 3 && $service->award_points === 1) {
                    $points += $service->points_awarded;
                }
                
                $booking = new Booking;
                if(count($data['service']) > 1) {
                    $booking->type = 'multiple';
                } else {
                    $booking->type = 'single';
                }
                $booking->type_id = $type_id;
                $booking->client_id = $data['client_id'];
                $booking->location_id = $location->id;
                $booking->service_id = $service_id;
                
                //insert multiple staff or just one
                if($data['staff'][0] != 'undefined') {
                    if($booking_options->multiple_staff != 0) {
                        $booking->staff_id = $booking_staff[$service_index];
                    } else {
                        $booking->staff_id = $booking_staff[0];
                    }
                } else {
                    $booking->staff_id = $booking_staff[0];
                }

                $booking->booking_date = date('Y-m-d', strtotime($data['booking_date']));
                $booking->start = $booking_start;
                $service_duration = date('H:i', strtotime($service->service_details->service_length));
                $duration_hours = date('H', strtotime($service_duration));
                $duration_minutes = date('i', strtotime($service_duration));
                $booking_end = date('H:i', strtotime('+'.$duration_hours.' hour +'.$duration_minutes.' minutes', strtotime($booking_start)));

                $check_schedule = $this->checkScheduleForChanges($booking->staff_id, date('Y-m-d', strtotime($data['booking_date'])), $booking_start, $booking_end);

                if($check_schedule['status'] != 1) {
                    return ['status' => 0, 'message' => trans('salon.booking_already_exists')];
                }

                $booking->booking_end = $booking_end;
                $booking->waiting_list = isset($data['waiting_list']) ? 1 : 0;
                $booking->save();
                
                $booking_details = new BookingDetails;
                $booking_details->booking_id = $booking->id;
                $booking_details->type = 0;
                
                if($calendar_options->appointment_colors === 'status') {
                    $color = '#ffe763';
                } else {
                    $color = $service->service_category->cat_color;
                }
                
                if(isset($data['waiting_list'])) {
                    $cal_colors = CalendarColors::where('salon_id', $location->salon_id)->first();
                    $color = $cal_colors->status_waiting_list;
                    $status = 'waiting_list';
                } else {
                    $status = 'status_booked';
                }
                
                $booking_details->status = $status;
                $booking_details->color = $color;
                $booking_details->loyalty_points = isset($points) ? $points : 0;
                $booking_details->save();

                $booking_start = $booking_end;
                $client_details = Clients::find($data['client_id']);
                $service_index++;
            }
            
            $booking_price = new BookingPrice;
            $booking_price->location_id = $location->id;
            $booking_price->booking_id = $type_id;
            if($booking->type === 'multiple') {
                $booking_price->type = 'multiple';
            } else {
                $booking_price->type = 'single';
            }
            $booking_price->code_used = $data['discount_code'] ?? 0;
            $booking_price->total_base_price = $data['base_price'];
            $booking_price->price = $data['total_price'];
            $booking_price->currency = $salon->currency;
            $booking_price->save();

            $client_points = $client_details->loyalty_points;
            $client_details->loyalty_points = $client_points + $data['points_awarded'];
            $client_details->save();
            
            if(isset($data['discount_code'])) {
                $code = Vouchers::where('code', $data['discount_code'])->first();
                if($code != null && $code->amount > 1) {
                    $code->amount -= 1;
                    if ($code->amount <= 0) {
                        $code->delete();
                    } else {
                        $code->save();
                    }
                } else {
                    return ['status' => 0, 'message' => trans('salon.code_not_found_or_expired')];
                }
            }
            
            DB::commit();

            $booking_confirmation = $this->sendBookingConfirmation($client_details, $booking);
            if($booking_confirmation['status'] != 1) {
                return ['status' => 0, 'message' => $booking_confirmation['message']];
            }
            
            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

    public function checkScheduleForChanges($staff_id, $date, $start, $end) {

        $staff = User::find($staff_id);
        $location = Location::find($staff->location_id);
        $booking_list = Booking::where('location_id', $location->id)->where('staff_id', $staff->id)->where('booking_date', $date)->get();

        foreach($booking_list as $booking) {
            $booking_start = date('H:i', strtotime($booking->start));
            $booking_end = date('H:i', strtotime($booking->booking_end));

            if(($start <= $booking_start && $end > $booking_start) || ($start > $booking_start && $start < $booking_end)) {
                return ['status' => 0];
            }
        }

        return ['status' => 1];

    }
    
    public function loginClient($data) {
        
        if(Auth::attempt(array('email' => $data['login_email'], 'password' => $data['login_password'], 'email_verified' => 1))) {
            
            $user = Auth::user();
            
            $user_lang = $user->language;
            $lang_iso = Languages::find($user_lang);
            
            Session::put('language', $lang_iso->language_iso);
            
            //check if user is client of selected location
            $clients = Clients::where('location_id', $data['booking_location'])->where('email', $data['login_email'])->get();
            if(!$clients->isNotEmpty()) {
                $client = $this->createNewClient($data);
            } else {
                foreach($clients as $client) {
                    $client->user_id = $user->id;
                    $client->save();
                }
                $client = $clients[0];
            }

            return ['status' => 1, 'message' => trans('auth.login_success'), 'client' => $client];
            
        }
        
        return ['status' => 0, 'message' => trans('auth.login_error')];
       
    }
    
    public function clientAddNewBooking($location, $data, $booking_options) {
        try {
            $salon = Salons::find($location->salon_id);
            $booking_date = date('Y-m-d', strtotime($data['booking_date']));
            $booking_list = Booking::where('location_id', $location->id)->where('booking_date', $booking_date)->get();
            $calendar_options = CalendarOptions::where('salon_id', $location->salon_id)->first();

            if(!Auth::user()) {
                if($data['account'] === '1' || $data['account'] === '0') {
                    $client = $this->createNewClient($data);
                } else if ($data['account'] === '2') {
                    $client = $this->loginClient($data);
                }

                if($client['status'] === 1) {
                    $client_obj = $client['client'];
                } else {
                    return ['status' => 0, 'message' => $client['message']];
                }
            } else {
                $clients = Clients::where('location_id', $data['booking_location'])->where('email', Auth::user()->email)->get();

                $user_account = Auth::user();
                $user_account->user_extras->phone_number = $data['phone'] ?? $user_account->user_extras->phone_number;
                $user_account->user_extras->address = $data['address'] ?? $user_account->user_extras->address;
                $user_account->user_extras->gender = $data['gender'] ?? $user_account->user_extras->gender;
                $user_account->user_extras->save();

                if(!$clients->isNotEmpty()) {
                    $client = new Clients;
                    $client->user_id = $user_account->id;
                    $client->location_id = $data['booking_location'];
                    $client->first_name = $user_account->user_extras->first_name;
                    $client->last_name = $user_account->user_extras->last_name;
                    $client->email = $user_account->email;
                    $client->phone = $data['phone'] ?? $user_account->user_extras->phone_number;
                    $client->address = $data['address'] ?? $user_account->user_extras->address;
                    $client->gender = $data['gender'] ?? $user_account->user_extras->gender;
                    $client->custom_field_1 = $data['custom_field_1'] ?? $client->custom_field_1;
                    $client->custom_field_2 = $data['custom_field_2'] ?? $client->custom_field_2;
                    $client->custom_field_3 = $data['custom_field_3'] ?? $client->custom_field_3;
                    $client->custom_field_4 = $data['custom_field_4'] ?? $client->custom_field_4;
                    $client->save();

                    $client_obj = $client;

                } else {
                    foreach($clients as $client) {
                        $client->user_id = $user_account->id;
                        $client->save();
                    }
                    $client_obj = $clients[0];
                }
            }

            if($data['staff'][0] === null) {
                $staff_list = User::where('location_id', $location->id)->get();
                $min = 99999;
                $booking_staff = [];
                foreach($staff_list as $staff) {
                    $min_staff[$staff->id] = 0;
                    if($staff->user_extras->available_booking == 1) {
                        foreach($booking_list as $list) {
                            if($list->staff_id === $staff->id) {
                                $min_staff[$staff->id] += 1;
                            }
                        }
                        if($min > $min_staff[$staff->id]) {
                            $min = $min_staff[$staff->id];
                            $min_id = $staff->id;
                        }
                    }
                }
                $booking_staff[] = $min_id;
            } else {
                $booking_staff = $data['staff'];
            }

            DB::beginTransaction();
            
            $service_index = 0;
            $booking_start = $data['booking_from'];
            $type_id = base64_encode(random_bytes(7));
            foreach($data['service'] as $service) {
                //get service id from array
                $service_string = explode('-', $service);
                $service_id = $service_string[1];
                $service = Service::find($service_id);

                $booking = new Booking;
                if(count($data['service']) > 1) {
                    $booking->type = 'multiple';
                } else {
                    $booking->type = 'single';
                }
                $booking->type_id = $type_id;
                $booking->client_id = $client_obj->id;
                $booking->location_id = $location->id;
                $booking->service_id = $service_id;
                
                //insert multiple staff or just one
                if($data['staff'][0] != null) {
                    if($booking_options->multiple_staff != 0) {
                        $booking->staff_id = $booking_staff[$service_index];
                    } else {
                        $booking->staff_id = $booking_staff[0];
                    }
                } else {
                    $booking->staff_id = $booking_staff[0];
                }

                $booking->booking_date = date('Y-m-d', strtotime($data['booking_date']));
                $booking->start = $booking_start;
                $service_duration = date('H:i', strtotime($service->service_details->service_length));
                $duration_hours = date('H', strtotime($service_duration));
                $duration_minutes = date('i', strtotime($service_duration));
                $booking_end = date('H:i', strtotime('+'.$duration_hours.' hour +'.$duration_minutes.' minutes', strtotime($booking_start)));

                $check_schedule = $this->checkScheduleForChanges($booking->staff_id, date('Y-m-d', strtotime($data['booking_date'])), $booking_start, $booking_end);

                if($check_schedule['status'] != 1) {
                    return ['status' => 0, 'message' => trans('salon.booking_already_exists')];
                }

                $booking->booking_end = $booking_end;
                $booking->waiting_list = isset($data['waiting_list']) ? 1 : 0;
                $booking->save();
                
                $booking_details = new BookingDetails;
                $booking_details->booking_id = $booking->id;
                $booking_details->type = 0;
                
                if($calendar_options->appointment_colors === 'status') {
                    $color = '#ffe763';
                } else {
                    $color = $service->service_category->cat_color;
                }
                
                if(isset($data['waiting_list'])) {
                    $cal_colors = CalendarColors::where('salon_id', $location->salon_id)->first();
                    $color = $cal_colors->status_waiting_list;
                    $status = 'waiting_list';
                } else {
                    $status = 'status_booked';
                }
                
                $booking_details->status = $status;
                $booking_details->color = $color;
                $booking_details->loyalty_type = isset($data['loyalty_type']) ? $data['loyalty_type'] : null;
                $booking_details->loyalty_points = isset($data['points_awarded']) ? $data['points_awarded'] : null;
                $booking_details->save();
                
                $booking_start = $booking_end;
                $client_details = Clients::find($client_obj->id);
                $service_index++;
            }
            
            $booking_price = new BookingPrice;
            $booking_price->location_id = $location->id;
            $booking_price->booking_id = $type_id;
            if($booking->type === 'multiple') {
                $booking_price->type = 'multiple';
            } else {
                $booking_price->type = 'single';
            }
            $booking_price->code_used = $data['discount_code'] ?? 0;
            $booking_price->points_used = $data['points_used'] ?? null;
            $booking_price->selected_discount = $data['selected_discount'] ?? null;
            $booking_price->free_service = $data['free_service'] ?? null;
            $booking_price->total_base_price = $data['base_price'];
            $booking_price->price = $data['total_price'];
            $booking_price->currency = $salon->currency;
            $booking_price->save();
            
            if(isset($data['discount_code'])) {
                
                $code = DiscountCodes::where('code', $data['discount_code'])->first();
                
                if($code != null) {
                    if($code->client_id != null) {
                        $code->delete();
                    } else {
                        $code->amount -= 1;
                        if($code->amount <= 0) {
                            $code->delete();
                        } else {
                            $code->save();
                        }
                    }
                }
            }

            if($data['loyalty_status'] === '1') {
                $loyalty_program = LoyaltyPrograms::where('location_id', $data['booking_location'])->first();
                if($loyalty_program->loyalty_type === 1) {
                    $client_obj->arrival_points = $client_obj->arrival_points - $loyalty_program->arrivals;
                } else if ($loyalty_program->loyalty_type === 2 && $data['free_service'] != null) {
                    $client_obj->loyalty_points = $client_obj->loyalty_points - $loyalty_program->arrivals;
                } else if ($loyalty_program->loyalty_type === 3 && $data['points_used'] != 0 && $data['selected_discount'] != null) {
                    $client_obj->loyalty_points = $client_obj->loyalty_points - $data['points_used'];
                }
                $client_obj->save();
            }
            
            DB::commit();

            $booking_confirmation = $this->sendBookingConfirmation($client_details, $booking);

            if($booking_confirmation['status'] != 1) {
                return ['status' => 0, 'message' => $booking_confirmation['message']];
            }
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

    public function sendBookingConfirmation($client, $booking) {
        try {
            $reminder = Reminders::where('location_id', $client->location_id)->where('reminder_type', 2)->first();
            if($reminder != null) {
                $template = MarketingTemplate::where('location_id', $client->location_id)->where('id', $reminder->email_template)->first();
                if($client->email_reminders === 1 && $reminder != null && $template != null) {
                    $user = $client->account;
                    $unique_id = substr(md5(rand()), 0, 50);
                    $user->pin = $unique_id;
                    $user->save();

                    $user->notify(new BookingConfirmation($booking, $user));
                }
            }
            return ['status' => 1];
        } catch (Exception $exc) {
            return  ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function addClientNote($customer, $note) {
        
        $customer->note = $note;
        $customer->save();
        
        return ['status' => 1];
        
    }
    
    public function updateCustomStyles($styles, $data) {
        
        $styles->action_button = $data['action_button'];
        $styles->select_button = $data['select_button'];
        $styles->group_header = $data['group_header'];
        $styles->summary = $data['summary_color'];
        $styles->background = $data['background_color'];
        $styles->save();
        
        return ['status' => 1];
        
    }
    
    public function rescheduleBooking($booking, $data) {

        try {
            if($booking->type === 'multiple') {
                $booking_list = Booking::where('type_id', $booking->type_id)->get();
                $start = $data['from'];
                foreach($booking_list as $booking_reschedule) {
                    $hours = date('H', strtotime($booking_reschedule->service->service_details->service_length));
                    $minutes = date('i', strtotime($booking_reschedule->service->service_details->service_length));

                    $booking_end = date('H:i', strtotime('+'.$hours.' hour '.$minutes.' minute',strtotime($start)));

                    $booking_reschedule->booking_date = date('Y-m-d', strtotime($data['date']));
                    $booking_reschedule->start = $start;
                    $booking_reschedule->booking_end = $booking_end;
                    $booking_reschedule->save();

                    $resc_booking_details = BookingDetails::where('booking_id', $booking_reschedule->id)->first();
                    $resc_booking_details->status = 'status_rebooked';
                    $resc_booking_details->color = '#F2DCDB';
                    $resc_booking_details->save();

                    $start = $booking_end;
                }
            } else {
                $booking->booking_date = date('Y-m-d', strtotime($data['date']));
                $booking->start = $data['from'];
                $booking->booking_end = $data['to'];
                $booking->save();

                $booking_details = BookingDetails::where('booking_id', $booking->id)->first();
                $booking_details->status = 'status_rebooked';
                $booking_details->color = '#F2DCDB';
                $booking_details->save();
            }

            return ['status' => 1];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }


    }
    
    public function editBooking($location, $data) {
        
        $booking_date = date('Y-m-d', strtotime($data['booking_date']));
        $booking_list = Booking::where('location_id', $location->id)->where('booking_date', $booking_date)->get();
        
        if($booking = Booking::find($data['booking_id'])) {
            
            $booking->staff_id = $data['booking_staff'];
            $booking->service_id = $data['booking_service'];
            $booking->booking_date = date('Y-m-d', strtotime($data['booking_date']));
            $booking->start = $data['booking_from'];
            $booking->booking_end = $data['booking_to'];
            $booking->save();
            
            return ['status' => 1];
            
        }
        
        return ['status' => 0];
    }
    
    public function getStaff($data) {

        $staff_list = [];
        $staff_list_final = [];
        $location = Location::find($data['location']);
        $salon = Salons::find($location->salon_id);
        $booking_policy = BookingPolicy::where('salon_id', $salon->id)->first();
        $services_count = count($data['services']);

        try {
            foreach($data['services'] as $service) {
                $service_list = explode('-', $service);
                $service_id = $service_list[1];
                
                $staff_id = ServiceStaff::select('user_id')->where('location_id', $data['location'])->where('service_id', $service_id)->get();
                $service = Service::find($service_id);

                foreach($staff_id as $staff) {
    
                    $user = User::find($staff->user_id);
                    
                    if($user->staff_hours->isNotEmpty() && $user->schedule_options->isNotEmpty()) {
                        if($booking_policy->multiple_staff === 0) {
                            $staff_list[$user->id][] = [
                                'array_key' => $user->id,
                                'user_id' => $user->id,
                                'first_name' => $user->user_extras->first_name,
                                'last_name' => $user->user_extras->last_name,
                                'avatar' => $user->user_extras->photo,
                            ];
                        } else {
                            $staff_list_final[$service->id][] = [
                                'service_id' => $service->id,
                                'service_name' => $service->service_details->name,
                                'user_id' => $user->id,
                                'first_name' => $user->user_extras->first_name,
                                'last_name' => $user->user_extras->last_name,
                                'avatar' => $user->user_extras->photo
                            ];
                        }
                    }
                }
            }

            //return $staff_list_final;
            if($booking_policy->multiple_staff === 0) {
                foreach($staff_list as $list) {
                    
                    $count_list = count($list);
                    if($count_list === $services_count) {
                        $staff_list_final[] = [
                            'user_id' => $list[0]['array_key'],
                            'first_name' => $list[0]['first_name'],
                            'last_name' => $list[0]['last_name'],
                            'avatar' => $list[0]['avatar']
                        ];
                    }
                }
            }
            //$reversed_list = array_reverse($staff_list_final, true);
            
            $json = [];
            foreach($staff_list_final as $key => $value) {
                $json[] = [$key => $value];
            }
            
            return ['status' => 1, 'staff' => $json];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function createNewClient($data) {

        try {
            
            DB::beginTransaction();

            if(isset($data['location'])) {
                $location = $data['location'];
            } else if (isset($data['booking_location'])) {
                $location = $data['booking_location'];
            }

            $location = Location::find($location);

            $check_users = User::where('email', $data['email'])->first();
            
            if(isset($data['account']) && $data['account'] === '1') {
                //create new user account
                if($check_users === null) {
                    if($data['password'] === $data['password_confirm']) {
                        $user_account = new User;
                        $user_account->email = $data['email'];
                        $user_account->password = Hash::make($data['password']);
                        $user_account->language = Auth::user()->language;
                        $user_account->email_verified = 0;
                        $user_account->salon_id = null;
                        $user_account->location_id = null;
                        $user_account->save();
                        
                        $user_id = $user_account->id;
                        
                        $user_account->attachRole(7);
                        
                        $user_account_extras = new UserExtras;
                        $user_account_extras->user_id = $user_id;
                        $user_account_extras->first_name = isset($data['first_name']) ? $data['first_name'] : null;
                        $user_account_extras->last_name = isset($data['last_name']) ? $data['last_name'] : null;
                        $user_account_extras->phone_number = isset($data['phone']) ? $data['phone'] : null;
                        $user_account_extras->address = isset($data['address']) ? $data['address'] : null;
                        $user_account_extras->gender = isset($data['gender']) ? $data['gender'] : 0;
                        $user_account_extras->save();
                        
                        //check if client has made any previous bookings with this email address and connect them with new user account
                        $client_location_list = Clients::where('email', $data['email'])->get();
                        foreach($client_location_list as $val) {
                            $val->user_id = $user_id;
                            $val->save();
                        }
                        
                    } else {
                        return ['status' => 0, 'message' => trans('salon.passwords_mismatch')];
                    }
                    
                } else {

                    $client_locations = Clients::where('user_id', $check_users->id)->orWhere('email', $data['email'])->get();

                    foreach($client_locations as $location_check) {

                        if($location_check->location_id == $location->id) {
                            return ['status' => 0, 'message' => trans('salon.client_has_account')];
                        } else {
                            $user_id = $check_users->id;
                        }
                    }
                }
                
            } else {
                $client_existing = Clients::where('email', $data['email'])->where('location_id', $location->id)->first();
                if($client_existing != null) {
                    return ['status' => 0, 'message' => trans('salon.email_address_taken')];
                }
                $user_id = null;
            }

            $client = new Clients;
            $client->user_id = $user_id;
            $client->location_id = $location->id;
            $client->first_name = $data['first_name'];
            $client->last_name = $data['last_name'];
            $client->email = $data['email'];
            $client->phone = isset($data['phone']) ? $data['phone'] : null;
            $client->address = isset($data['address']) ? $data['address'] : null;
            $client->gender = isset($data['gender']) ? $data['gender'] : 0;
            $client->custom_field_1 = isset($data['custom_field_1']) ? $data['custom_field_1'] : null;
            $client->custom_field_2 = isset($data['custom_field_2']) ? $data['custom_field_2'] : null;
            $client->custom_field_3 = isset($data['custom_field_3']) ? $data['custom_field_3'] : null;
            $client->custom_field_4 = isset($data['custom_field_4']) ? $data['custom_field_4'] : null;
            $client->save();
            
            DB::commit();

            $reminder = Reminders::where('location_id', $location->id)->where('reminder_type', 7)->first();

            if($reminder != null) {
                $client->notify(new NewClient($client->first_name, $client->last_name,$location));
            }

            return ['status' => 1, 'client' => $client, 'message' => trans('salon.client_added')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function getMaxEndWorkHour($user_selection, $employees) {
        
        $end_work_hour = '';

        foreach ($user_selection as $selection) {
            $employee_end_work = $employees[$selection['employee_id']]['end'];
    
            if ($end_work_hour != '') {
                if ($employee_end_work > $end_work_hour) {
                    $end_work_hour = $employee_end_work;
                }
            } else {
                $end_work_hour = $employee_end_work;
            }
        }
    
        return $end_work_hour;
    }
    
    public function getAvailableTimes($selected_date, $user_selection, $employees, $services, $start_time, $end_time, $available_times_array) {
        $service_end = '';
        $check_time = true;
        
        $rand_user = User::find($user_selection[0]['employee_id']);
        $salon = Salons::find($rand_user->salon_id);
        $booking_slot = $salon->booking_policy->booking_slot;
        $current_date = new \DateTime("now", new \DateTimeZone($salon->time_zone));

        foreach($user_selection as $selection) {

            $booking_list = Booking::where('staff_id', $selection['employee_id'])->where('booking_date', $selected_date)->count();

            $employee = $employees[$selection['employee_id']];
            $employee_lunch_start = date('H:i:s', strtotime($employee['lunch_start']));
            $employee_lunch_end = date('H:i:s', strtotime($employee['lunch_end']));
            $employee_start_work = date('H:i:s', strtotime($employee['start']));
            $employee_end_work = date('H:i:s', strtotime($employee['end']));
            $service = $services[$selection['service_id']];
            $service_start = $start_time;
            
            if($service_end != '') {
                $service_start = $service_end;
            }
    
            $serv_dur_hour = date('H', strtotime($service['duration']));
            $serv_dur_mins = date('i', strtotime($service['duration']));
            $service_end = date('H:i:s', strtotime('+'.$serv_dur_hour.' hour +'.$serv_dur_mins. ' minutes', strtotime($service_start)));

            $bookings = Booking::where('staff_id', $selection['employee_id'])->where('booking_date', $selected_date)
                ->whereRaw('(((? < start) AND (? <= start)) OR ((? >= booking_end) AND (? >= booking_end)))',
                array($service_start, $service_end, $service_start, $service_end))->count();

            if($booking_list != 0) {
                if (($selected_date != date('Y-m-d') && $current_date->format('H:i:s') >= $service_start) || !($service_start >= $employee_start_work && $service_end <= $employee_end_work && $bookings == $booking_list) || !($service_end <= $employee_lunch_start || $service_start >= $employee_lunch_end)) {
                    $check_time = false;
                }
            } else {
                if (($selected_date === date('Y-m-d') && $current_date->format('H:i:s') >= $service_start) || !($service_start >= $employee_start_work && $service_end <= $employee_end_work) || !($service_end <= $employee_lunch_start || $service_start >= $employee_lunch_end)) {
                    $check_time = false;
                }
            }

        }
        
        if($check_time && ($service_end < $end_time)) {
            
            $this->timetable_multiple[] = [
                'from' => date('H:i', strtotime($start_time)),
                'to' => date('H:i', strtotime('+'.$serv_dur_hour.' hour +'.$serv_dur_mins. ' minutes', strtotime($service_start)))
            ];
            
            $start_time = date('H:i', strtotime('+'.$booking_slot.' minutes', strtotime($start_time)));
            
        } else {
        
            $start_time = date('H:i', strtotime('+5 minutes', strtotime($start_time)));
            
        }

        if($service_end <= $end_time) {
            $this->getAvailableTimes($selected_date, $user_selection, $employees, $services, $start_time, $end_time, $this->timetable_multiple);
        }

        return $this->timetable_multiple;

    }
    
    public function getServiceList($location, $category, $groups, $client_loyalty = null) {
        
        $groups_array = [];

        $location_obj = Location::find($location);
        $salon = Salons::find($location_obj->salon_id);

        foreach($groups as $group) {

            $subgroups_array = [];
            $services_array = [];
            
            $group_services = Service::where('location_id', $location)->where('category', $category->id)->where('group', $group->id)->orderBy('order', 'ASC')->get();

            foreach($group_services as $group_service) {

                if($group_service->service_staff->isNotEmpty()) {
                    if($group->id === $group_service->group) {

                        if ($location_obj->happy_hour === 1) {
                            $current_date = new \DateTime("now", new \DateTimeZone($salon->time_zone));
                            $current_day = $current_date->format('l');
                            $current_time = $current_date->format('H:i');
                            $base_price = $group_service->service_details->base_price;

                            foreach($location_obj->happy_hour_location as $happy_hour_day) {
                                if($happy_hour_day->day === $current_day && $current_time >= $happy_hour_day->start && $current_time <= $happy_hour_day->end) {
                                    $discount = '0.'.$location_obj->happy_hour_discount;
                                    $discount_price = $base_price * $discount;
                                    $end_price = $base_price - $discount_price;
                                } else {
                                    $end_price = $base_price;
                                }
                            }

                        } else {
                            $end_price = $group_service->service_details->base_price;
                        }
                        
                        if($group_service->sub_group != null) {
                            $subgroup_id = $group_service->service_subgroup->id;
                            $subgroup_name = $group_service->service_subgroup->name;
                        } else {
                            $subgroup_id = null;
                            $subgroup_name = null;
                        }
                        
                        $service_arr[$group->id][] = [
                            'group_name'=> $group->name,
                            'service'=> [
                                'service_id' => $group_service->id,
                                'subgroup_id' => $subgroup_id,
                                'subgroup_name' =>$subgroup_name,
                                'service_name' => $group_service->service_details->name,
                                'service_price' => $end_price
                            ]
                        ];
                    }
                    
                }
                
            }
        }
        
        return $service_arr;
    }
    
    public function redeemCode($data, $code) {

        $services_for_discount = [];
        $services_excluded = [];
        $discount_applied_to = [];
        //check if services can be discounted
        foreach($data['services'] as $service) {
            if ($service_obj = Service::find($service)) {
                $location = Location::find($service_obj->location_id);
                $salon = Salons::find($location->salon_id);
                if($service_obj->allow_discounts === 1) {
                    $services_for_discount[] = $service_obj;
                    $discount_applied_to[] = $service_obj->service_details->name;
                } else {
                    $services_excluded[] = $service_obj;
                }
            }
        }

        if($services_excluded === null) {
            return ['status' => 0, 'message' => trans('salon.selected_services_ineligible')];
        }

        $current_date = date('Y-m-d');

        if($current_date <= $code->expire_date) {

            $discounted_price = $this->returnPrice($services_for_discount, $services_excluded, $code);

            return ['status' => 1, 'price' => $discounted_price, 'discount_applied_to' => $discount_applied_to];

        }

        return ['status' => 0, 'message' => trans('salon.code_not_found_or_expired')];

    }
    
    public function returnPrice($services_for_discount, $services_excluded, $code) {
        $total_price_disc = 0;
        $total_price = 0;
        
        foreach($services_for_discount as $discount_service) {
            $total_price_disc += $discount_service->service_details->base_price;    
        }
        $discount = '0.'.$code->discount;
        $base_discount = $total_price_disc * $discount;
        $end_price = $total_price_disc - $base_discount;

        foreach($services_excluded as $excluded_service) {
            $total_price += $excluded_service->service_details->base_price;
        }
        
        $total_price += $end_price;
        
        return ['status' => 1, 'price' => $total_price];
    }
    
    public function calculatePoints($data) {
        
        $points = 0;
        $price = 0;
        
        try {
            
            foreach($data['services'] as $service) {
                $service = Service::find($service);
                $price += $service->service_details->base_price;
                
                if($service->award_points === 1) {
                    $points += $service->points_awarded;
                }
                
            }
            
            //calculate points for money spent (1val = number of points)
            $location = Location::find($service->location_id);
            $points_ratio = LoyaltyManagement::where('salon_id', $location->salon_id)->first();
            $points_rest = $points_ratio->money_spent * $price;
            $points += $points_rest;
        
            return ['status' => 1, 'points' => $points];
            
        } catch (Exception $exc) {
            
            return ['status' => 0];
            
        }
    }
    
    public function checkWaitingList($location_id) {

        try {

            $waiting_list_bookings = Booking::where('location_id', $location_id)->where('booking_date', '>', date('Y-m-d'))->where('waiting_list', 1)->groupBy('type_id')->get();

            $reschedule_dates = [];

            foreach ($waiting_list_bookings as $waiting_list) {

                $current_date = date('Y-m-d');

                if ($waiting_list->type === 'multiple') {

                    $multiple_bookings = Booking::where('type_id', $waiting_list->type_id)->get();

                    while ($current_date < $waiting_list->booking_date) {

                        $waiting_list_status = true;

                        foreach ($multiple_bookings as $multiple_booking) {

                            if ($waiting_list_status) {
                                //find bookings for each day and check if waiting list booking can fit into a schedule
                                $check_booking = Booking::where('booking_date', $current_date)->where('location_id', $location_id)->where('staff_id', $multiple_booking->staff_id)->get();

                                foreach ($check_booking as $staff_booking) {
                                    if (($multiple_booking->start < $staff_booking->start && $multiple_booking->booking_end <= $staff_booking->booking_end) || ($multiple_booking->start >= $staff_booking->booking_end)) {
                                        $waiting_list_status = true;
                                    } else {
                                        $waiting_list_status = false;
                                        break;
                                    }
                                }
                            }

                        }

                        if ($waiting_list_status) {
                            $reschedule_dates[$waiting_list->id][] = $current_date;
                        }

                        $current_date = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));

                    }

                } else {

                    while ($current_date < $waiting_list->booking_date) {
                        //get all bookings at the current day for the same staff member
                        $check_booking = Booking::where('booking_date', $current_date)->where('location_id', $location_id)->where('staff_id', $waiting_list->staff_id)->get();

                        foreach ($check_booking as $staff_booking) {
                            if (($waiting_list->start < $staff_booking->start && $waiting_list->booking_end <= $staff_booking->booking_end) || ($waiting_list->start >= $staff_booking->booking_end)) {
                                $waiting_list_status = true;
                            } else {
                                $waiting_list_status = false;
                                break;
                            }
                        }

                        if ($waiting_list_status) {
                            $reschedule_dates[$waiting_list->id][] = $current_date;
                        }

                        $current_date = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));

                    }

                }

            }

            //send notifications
            foreach ($reschedule_dates as $key => $reschedule_date) {

                $booking = Booking::find($key);
                if ($booking->type === 'multiple') {
                    $booking_multiple_list = Booking::where('type_id', $booking->type_id)->get();
                    foreach ($booking_multiple_list as $change_status) {
                        $change_status->waiting_list = 0;
                        $change_status->save();
                    }
                }
                $booking->waiting_list = 0;
                $token = substr(md5(rand()), 0, 40);
                $booking->reschedule_token = $token;
                $booking->save();

                $client = Clients::find($booking->client_id);

                if ($client->email != null) {
                    $available_dates = $reschedule_dates[$key];
                    $reminder = Reminders::where('location_id', $booking->booking_location->id)->where('reminder_type', 3)->first();
                    if($reminder != null) {
                        $template = MarketingTemplate::where('location_id', $booking->booking_location->id)->where('id', $reminder->email_template)->first();
                        if($template != null) {
                            $client->notify(new WaitingListNotification($booking, $available_dates));
                        }
                    }
                }

            }
            return ['status' => 1, 'message' => $reschedule_dates];
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function rescheduleWaitingList($booking, $date) {
        
        try {
            $location = Location::find($booking->location_id);
            $salon = Salons::find($location->salon_id);
            $reschedule_color = CalendarColors::select('status_rebooked')->where('salon_id', $salon->id)->first();

            $date_format = date('Y-m-d', $date);
            
            if($booking->type === 'multiple') {
                //fetch all bookings connected to main booking
                $booking_list = Booking::where('type_id', $booking->type_id)->get();

                foreach($booking_list as $multiple_booking) {
                    $multiple_booking->booking_date = $date_format;
                    $multiple_booking->reschedule_token = null;
                    $multiple_booking->save();
                    
                    $multiple_booking_details = $multiple_booking->booking_details;
                    $multiple_booking_details->color = $reschedule_color['status_rebooked'];
                    $multiple_booking_details->save();
                }
            } else {
                $booking->booking_date = $date_format;
                $booking->reschedule_token = null;
                $booking->save();
                
                $booking_details = $booking->booking_details;
                $booking_details->color = $reschedule_color['status_rebooked'];
                $booking_details->save();
            }
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc];
        }
    }
    
    public function getRequiredFields($salon_id, $location_id) {
        
        try {
            
            $booking_fields = CustomFields::where('location_id', $location_id)->where('field_location', 'booking')->get();
            $booking_fields_arr = [];
            
            foreach($booking_fields as $booking_field) {
                if($booking_field->field_type === '1') {
                    $booking_fields_arr[$booking_field->id][] = [
                        'field_name' => $booking_field->field_name,
                        'field_title' => $booking_field->field_title,
                        'field_type' => 'text',
                    ];
                } else {
                    $select_options = SelectOptions::where('field_id', $booking_field->id)->get();
                    $select_options_arr = [];
                    foreach($select_options as $select_option) {
                        $select_options_arr[] = [
                            'option_name' => $select_option->option_name,
                            'option_title' => $select_option->option_title,
                        ];
                    }
                    $booking_fields_arr[$booking_field->id][] = [
                        'field_name' => $booking_field->field_name,
                        'field_title' => $booking_field->field_title,
                        'field_type' => 'select',
                        'select_options' => $select_options_arr
                    ];
                }
            }
            
            return ['status' => 1, 'fields' => $booking_fields_arr];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }

    public function getBooking($id) {

        try {
            $booking = Booking::find($id);
            $location = $booking->booking_location;
            $salon = Salons::find($location->salon_id);
            $booking_list = [];

            if($booking->type == 'multiple') {
                $bookings = Booking::where('location_id', $location->id)->where('type_id', $booking->type_id)->get();

                foreach($bookings as $booking_single) {
                    $booking_list[] = [
                        'id' => $booking_single->id,
                        'service' => $booking_single->service->service_details->name,
                        'service_id' => $booking_single->service_id,
                        'price' => $booking_single->service->service_details->base_price . ' ' . $salon->currency,
                        'date' => $booking_single->booking_date,
                        'start' => $booking_single->start,
                        'end' => $booking_single->booking_end,
                        'staff' => $booking->staff_id,
                        'location' => $booking->location_id
                    ];
                }
            } else {
                $booking_list[] = [
                    'id' => $booking->id,
                    'service' => $booking->service->service_details->name,
                    'service_id' => $booking->service_id,
                    'price' => $booking->service->service_details->base_price . ' ' . $salon->salon_currency,
                    'date' => $booking->booking_date,
                    'start' => $booking->start,
                    'end' => $booking->booking_end,
                    'staff' => $booking->staff_id,
                    'location' => $booking->location_id
                ];
            }
            return ['status' => 1, 'booking' => $booking_list];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => trans('salon.error_fetching_booking_list')];
        }

    }

    public function getMonthlyBookings($stats_date, $months) {

        try {

            $bookings = [];
            $start_date = $stats_date['start_date'];
            $end_date = $stats_date['end_date'];
            $month_list = $this->getMonthCount($start_date, $end_date);

            $location = Location::find(Auth::user()->location_id);
            $booking_list = Booking::where('location_id', $location->id)->where('created_at', '>=', $start_date)->where('created_at', '<', $end_date)->groupBy('type_id')->get();

            $month_list_complete = [];

            while($start_date < $end_date) {
                $i = date('n', strtotime($start_date));

                $cancelled_amount = 0;
                $completed_amount = 0;
                $completed_income = 0;

                if (array_key_exists($i, $months)) {
                    $month_list_complete[] = $months[$i];
                }

                foreach($booking_list as $booking) {
                    $month = date('n', strtotime($booking->created_at));

                    if($month == $i) {
                        if($booking->booking_details->status == 'status_cancelled') {
                            $cancelled_amount ++;
                        } else if ($booking->booking_details->status === 'status_complete' || $booking->booking_details->status === 'status_paid') {
                            $completed_income += $booking->pricing->price;
                            $completed_amount ++;
                        }
                    }

                }

                $bookings[$i] = [
                    'completed' => $completed_amount,
                    'cancelled' => $cancelled_amount,
                    'income' => $completed_income
                ];

                $start_date = date('Y-m-d', strtotime('+1 month', strtotime($start_date)));
            }

            return ['status' => 1, 'bookings' => $bookings, 'month_list' => $month_list_complete];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function getMonthCount($start, $end) {
        $months = [];
        while($start < $end) {
            $months[] = [
                date('n', strtotime($start)) => date('F', strtotime($start))
            ];
            $start = date('Y-m-d', strtotime('+1 month', strtotime($start)));
        }
        return $months;
    }

    public function getNextBooking() {

        try {

            $salon = Salons::find(Auth::user()->salon_id);

            $current_date = new \DateTime("now", new \DateTimeZone($salon->time_zone));

            $booking = Booking::where('location_id', Auth::user()->location_id)
                              ->where('booking_date', '>=', $current_date->format('Y-m-d'))
                              ->where('start', '>=', $current_date->format('H:i'))
                              ->first();

            return ['status' => 1, 'booking' => $booking];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function getTodaysBookings() {

        try {

            $bookings = Booking::where('location_id', Auth::user()->location_id)
                ->where('booking_date', '=', date('Y-m-d'))
                ->get();

            return ['status' => 1, 'bookings' => $bookings];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

}