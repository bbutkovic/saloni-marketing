<?php

namespace App\Repositories;

use App\Models\CalendarExports;
use App\Notifications\AppointmentCancellations;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Marketing\MarketingTemplate, Marketing\Reminders, Salons, Location, SalonService, LocalHours
};
use App\Models\Salon\{Service,LoyaltyManagement,LoyaltyPrograms};
use App\Models\Booking\{Booking,CalendarSettings,CalendarColors,CalendarOptions,Clients,DiscountCodes};
use App\Models\Clients\{ClientSettings,ClientLocations};

use App\Notifications\SendSlowDayPromo;
use DB,Log;
use Spatie\CalendarLinks\Link;

class CalendarRepository {
    
    public function getBookings($staff, $salon) {

        try {
            $calendar_options = CalendarOptions::where('salon_id', $salon->id)->first();

            $booking_list = [];
            $created_by = Auth::user()->user_extras->first_name;

            foreach($staff as $user) {
                $booking_data = Booking::where('staff_id', $user->id)->get();
                foreach($booking_data as $booking) {

                    $booking_details = $booking->booking_details;
                    $service = Service::find($booking->service_id);
                    $location = Location::find($booking->booking_location->id);
                    $salon = Salons::find($location->salon_id);

                    if($service->service_details != null) {

                        if($calendar_options->appointment_colors === 'status') {
                            $color = CalendarColors::select($booking->booking_details->status)->where('salon_id', $salon->id)->first();
                            $color = $color[$booking->booking_details->status];
                        } else {
                            if($booking->service->sub_group != null) {
                                $color = $booking->service->service_subgroup->subgroup_color;
                            } else if ($booking->service->sub_group === null && $booking->service->group != null) {
                                $color = $booking->service->service_group->group_color;
                            } else if ($booking->service->sub_group === null && $booking->service->group === null) {
                                $color = $booking->service->service_category->category_color;
                            }
                        }

                        $client = Clients::find($booking->client_id);

                        $booking_list[] = [
                            'id' => $booking->id,
                            'type' => $booking->type,
                            'type_id' => $booking->type_id,
                            'client_id' => $booking->client_id,
                            'status' => $booking_details->status,
                            'status_trans' => trans('salon.'.$booking_details->status),
                            'color' => $color,
                            'title' => $service->service_details->name,
                            'price' => $booking->pricing->price . ' ' . $salon->currency,
                            'duration' => $service->service_details->service_length,
                            'start' => $booking->booking_date . ' ' . $booking->start,
                            'end' => $booking->booking_date . ' ' . $booking->booking_end,
                            'customer_id' => $client['id'],
                            'customer_first_name' => $client['first_name'],
                            'customer_last_name' => $client['last_name'],
                            'customer_phone' => $client['phone'],
                            'customer_email' => $client['email'],
                            'customer_address' => $client['address'],
                            'customer_gender' => $client['gender'],
                            'customer_label' => isset($client->client_label->name) ? $client->client_label->name : '',
                            'customer_label_color' => isset($client->client_label->color) ? $client->client_label->color : '',
                            'custom_field_1' => $client['custom_field_1'],
                            'custom_field_2' => $client['custom_field_2'],
                            'custom_field_3' => $client['custom_field_3'],
                            'custom_field_4' => $client['custom_field_4'],
                            'customer_note' => $client['note'],
                            'staff_id' => $user->id,
                            'staff_first_name' => $user->user_extras->first_name,
                            'staff_last_name' => $user->user_extras->last_name,
                            'created_at' => $booking->created_at,
                            'updated_at' => $booking->updated_at,
                            'created_by' => $created_by
                        ];

                    }
                }
            }
            return ['status' => 1, 'booking_list' => $booking_list];
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function updateCalendar($data) {
        
        try {

            $settings = CalendarSettings::where('salon_id', Auth::user()->salon_id)->first();
            if($settings === null) {
                $settings = new CalendarSettings;
            }
            $settings->salon_id = Auth::user()->salon_id;
            $settings->client_notes = isset($data['client_notes']) ? $data['client_notes'] : null;
            $settings->phone_number = isset($data['phone_number']) ? $data['phone_number'] : null;
            $settings->email_address = isset($data['email']) ? $data['email'] : null;
            $settings->address = isset($data['address']) ? $data['address'] : null;
            $settings->new_client_indicator = isset($data['new_client_indicator']) ? $data['new_client_indicator'] : null;
            $settings->referrer = isset($data['referrer']) ? $data['referrer'] : null;
            $settings->save();
            
            $options = CalendarOptions::where('salon_id', Auth::user()->salon_id)->first();
            if($options === null) {
                $options = new CalendarOptions;
            }
            $options->salon_id = Auth::user()->salon_id;
            $options->appointment_interval = $data['appointment_interval'];
            $options->default_tab = $data['default_tab'];
            $options->appointment_colors = $data['appointment_colors'];
            $options->staff_photo = $data['staff_photo'];
            $options->drag_and_drop = $data['drag_and_drop'];
            $options->waiting_list = $data['waiting_list'];
            $options->appointment_number = $data['appointment_number'];
            $options->save();
            
            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function updateColors($data) {
        
        try {
            $colors = CalendarColors::where('salon_id', Auth::user()->salon_id)->first();
            $colors->status_booked = $data['status_booked'];
            $colors->status_complete = $data['status_complete'];
            $colors->status_waiting_list = $data['status_waiting_list'];
            $colors->status_arrived = $data['status_arrived'];
            $colors->status_confirmed = $data['status_confirmed'];
            $colors->status_cancelled = $data['status_cancelled'];
            $colors->status_rebooked = $data['status_rebooked'];
            $colors->status_noshow = $data['status_noshow'];
            $colors->status_paid = $data['status_paid'];
            $colors->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function updateBookingStatus($data) {
        try {
            $action = $data['action'];
            if($action === 'create_invoice') {
                $action = 'status_complete';
            }
            if($action === 'delete') {
                foreach($data['services'] as $booking_id) {
                    $booking = Booking::find($booking_id);
                    DB::beginTransaction();
                    $booking->booking_details->delete();
                    $booking->delete();
                    DB::commit();
                }
                return ['status' => 1, 'message' => trans('salon.booking_deleted')];
            } else {

                $main_booking = Booking::find($data['id']);
                $location = Location::find($main_booking->location_id);
                $salon = Salons::find($location->salon_id);
                $color = CalendarColors::select($action)->where('salon_id', $salon->id)->first();
                $booking_list = Booking::where('location_id', $location->id)->where('type_id', $main_booking->type_id)->get();

                if(isset($location->loyalty_program)) {
                    $loyalty_program = $location->loyalty_program;
                    $loyalty_type = $loyalty_program->loyalty_type;
                }

                $client = Clients::find($main_booking->client_id);

                if ($action === 'status_complete') {
                    if(isset($loyalty_program) && ($loyalty_type === 1 || $loyalty_type === 2)) {
                        $client->arrival_points = $client->arrival_points + $loyalty_program->arrival_points;
                    } else if(isset($loyalty_program) && $loyalty_type === 3) {
                        $awarded_points = $this->getServicePoints($booking_list);
                        $client->loyalty_points = $client->loyalty_points + $awarded_points;
                    }
                } else if ($action === 'status_cancelled') {
                    //send cancellation notification
                    $reminder = Reminders::where('location_id', $location->id)->where('reminder_type', 4)->first();
                    if($reminder != null) {
                        $template = MarketingTemplate::where('location_id', $location->id)->where('id', $reminder->email_template)->first();
                        if($template != null) {
                            $client->notify(new AppointmentCancellations($main_booking, $location));
                        }
                    }
                }
                $client->save();

                foreach($data['services'] as $booking_id) {
                    $booking = Booking::find($booking_id);
                    $booking->booking_details->status = $action;
                    $booking->booking_details->color = $color->$action;
                    $booking->booking_details->save();
                }

                $booking_repo = new BookingRepository;

                $booking_repo->checkWaitingList($location->id);

                return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
            }

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }

    public function getServicePoints($booking_list) {
        $points = 0;
        foreach($booking_list as $booking) {
            $points += $booking->service->points_awarded;
        }
        return $points;
    }

    public function addEventsToCalendar($code) {

        try {

            $client = new \Google_Client();
            $client->setAuthConfig('client_secret.json');
            $auth_code = $code;
            $access_token = $client->fetchAccessTokenWithAuthCode($auth_code);
            $client->setAccessToken($access_token);

            $user = Auth::user();
            $salon = Salons::find($user->salon_id);
            $location = Location::find($user->location_id);
            $calendar_exports = CalendarExports::where('location_id', $location->id)->where('user_id', $user->id)->first();

            foreach($location->booking as $booking) {

                if($calendar_exports != null && $calendar_exports->created_at > date('Y-m-d H:i:s') || $calendar_exports === null) {
                    $start = \DateTime::createFromFormat('Y-m-d H:i:s', $booking->booking_date . ' ' . $booking->start);
                    $end = \DateTime::createFromFormat('Y-m-d H:i:s', $booking->booking_date . ' ' . $booking->booking_end);

                    $event = new \Google_Service_Calendar_Event(array(
                        'summary' => $booking->service->service_details->name,
                        'location' => $location->address . ' ' . $location->city . ' ' . $location->zip,
                        'description' => $booking->staff->user_extras->first_name . ' ' . $booking->staff->user_extras->first_name,
                        'start' => array(
                            'dateTime' => $start->format(\DateTime::RFC3339_EXTENDED),
                            'timeZone' => $salon->time_zone,
                        ),
                        'end' => array(
                            'dateTime' => $end->format(\DateTime::RFC3339_EXTENDED),
                            'timeZone' => $salon->time_zone,
                        )
                    ));

                    $calendarId = 'primary';
                    $service = new \Google_Service_Calendar($client);
                    $service->events->insert($calendarId, $event);
                }

            }

            if($calendar_exports != null) {
                $calendar_exports->delete();
            }
            $new_export = new CalendarExports;
            $new_export->user_id = $user->id;
            $new_export->location_id = $location->id;
            $new_export->save();

            return ['status' => 1, 'message' => trans('salon.events_added_to_cal')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function getCalendarLinks($booking_id) {

        try {

            $booking = Booking::find($booking_id);
            $location = Location::find($booking->location_id);

            $from = \DateTime::createFromFormat('Y-m-d H:i:s', $booking->booking_date . ' ' . $booking->start);
            $to = \DateTime::createFromFormat('Y-m-d H:i:s', $booking->booking_date . ' ' . $booking->booking_end);

            $link = Link::create($booking->service->service_details->name, $from, $to)
                ->description($booking->staff->user_extras->first_name . ' ' . $booking->staff->user_extras->first_name)
                ->address($location->address . ' ' . $location->city . ' ' . $location->zip);

            $calendar_links = [
                'google' => $link->google(),
                'yahoo' => $link->yahoo(),
                'ics' => $link->ics()
            ];

            return ['status' => 1, 'calendar_links' => $calendar_links];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }
}