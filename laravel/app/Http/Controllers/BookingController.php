<?php

namespace App\Http\Controllers;

use App\Models\Salon\LoyaltyManagement;
use App\Models\Salon\LoyaltyPrograms;
use Illuminate\Http\Request;
use App\Repositories\{SalonRepository,InfoRepository,StaffRepository,BookingRepository,ClientRepository};
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\{Validator,Auth,Session};

use App\Models\{Salons,Location,StaffServices};
use App\Models\Salon\{Category,Group,SubCategory,Service,CustomFields,SelectOptions,Vouchers};
use App\Models\Booking\{Booking,BookingFields,CustomStyles,BookingPolicy,BookingDetails,Clients,CalendarOptions,CalendarSettings,DiscountCodes};
use App\Models\Clients\ClientLocations;
use App\User;
use DateTime;

class BookingController extends Controller
{

    protected $booking_repo;

    public function __construct() {
        $this->staff_repo = new StaffRepository;
        $this->booking_repo = new BookingRepository;
        $this->salon_repo = new SalonRepository;
        $this->client_repo = new ClientRepository;
    }

    public function getBookingSettings() {

        $salon = Salons::where('id', Auth::user()->salon_id)->first();

        $slots = [
            '5' => '5 min (optimal)',
            '10' => '10 min',
            '15' => '15 min',
            '20' => '20 min',
            '25' => '25 min',
            '30' => '30 min',
            '35' => '35 min',
            '40' => '40 min',
            '45' => '45 min',
            '50' => '50 min',
            '55' => '55 min',
            '60' => '60 min',
        ];

        $cancel_time = [
            '1' => trans('salon.cancel_one_hour'),
            '2' => trans('salon.cancel_two_hour'),
            '3' => trans('salon.cancel_one_day'),
            '4' => trans('salon.cancel_one_week'),
            '5' => trans('salon.cancel_one_month')
        ];

        $custom_styles = CustomStyles::where('salon_id', $salon->id)->first();

        $display_fields = CustomFields::where('location_id', Auth::user()->location_id)->where('field_location', 'booking')->get();

        return view('booking.bookingSettings', ['display_fields' => $display_fields, 'slots' => $slots, 'cancel_time' => $cancel_time, 'custom_styles' => $custom_styles]);
    }

    public function updateBookingPolicies(Request $request) {

        if($salon = Salons::find(Auth::user()->salon_id)) {

            $policies = $this->booking_repo->updateBookingPolicies($salon, $request->all());

            return ['status' => $policies['status'], 'message' => $policies['message']];

        }

        return ['status' => 0, 'message' => trans('salon.booking_update_failed')];

    }

    public function updateDisplayFields(Request $request) {

        $update_fields = $this->booking_repo->updateFields($request->all());

        return ['status' => $update_fields['status'], 'message' => $update_fields['message']];

    }

    public function addCustomFields(Request $request) {

        $custom_fields = $this->booking_repo->addCustomFields($request->all());

        if($custom_fields['status'] === 1) {
            return redirect()->back()->with('success_message', trans('salon.fields_added'))->with('active_tab', 3);
        }

        return redirect()->back()->with('error_message', $custom_fields['message']);

    }

    public function adminAddBooking() {

        $salon = Salons::find(Auth::user()->salon_id);

        $location = Location::find(Auth::user()->location_id);

        $currency = $salon->salon_currency->symbol;

        $category_list = Category::where('location_id', $location->id)->where('active', 1)->get();

        $booking_options = BookingPolicy::where('salon_id', $salon->id)->first();

        $booking_fields = CustomFields::where('location_id', $location->id)->where('field_status', 1)->get();

        $calendar_options = CalendarOptions::where('salon_id', $salon->id)->first();

        $calendar_settings = CalendarSettings::where('salon_id', $salon->id)->first();

        $week_start = $salon->week_starting_on;

        if($week_start == 2) {
            $week_start = 0;
        } else {
            $week_start = 1;
        }

        return view('booking.adminBooking', ['salon' => $salon, 'admin_location' => $location, 'category_list' => $category_list, 'booking_options' => $booking_options, 'booking_fields' => $booking_fields, 'admin_booking' => 1,
                                             'week_start' => $week_start, 'currency' => $currency, 'calendar_options' => $calendar_options, 'calendar_settings' => $calendar_settings, 'client_check' => 0]);

    }

    public function clientGetCategoryList($location_id) {
        if ($location = Location::find($location_id)) {

            $loyalty_message = null;

            if(Auth::user()) {
                $user = User::find(Auth::user()->id);
                $client_loyalty = $this->client_repo->checkLoyaltyProgram($user,$location);
                $loyalty_message = $client_loyalty;
            }
            $category_list = Category::where('location_id', $location->id)->where('active', 1)->get();

            return ['status' => 1, 'category_list' => $category_list, 'client_loyalty' => $loyalty_message];

        }

        return ['status' => 0, 'message' => trans('salon.location_not_found')];

    }

    public function getServices($location, $cat_id) {

        $category = Category::find($cat_id);

        $groups = Group::where('category_id', $cat_id)->where('active', 1)->get();

        if(Auth::user() && $user = User::find(Auth::user()->user_id)) {
            $client_loyalty = $this->client_repo->getLoyaltyPoints($user,$location);

            if($client_loyalty != null) {
                $service_list = $this->booking_repo->getServiceList($location, $category, $groups, $client_loyalty);
            }
        }

        $service_list = $this->booking_repo->getServiceList($location, $category, $groups);

        return ['status' => 1, 'services' => $service_list];

    }

    public function validateDate($date, $format = 'H:i') {

        $d = DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) == $date;

    }


    public function getStaffDates(Request $request) {

        $location = Location::find($request->location);
        $salon = Salons::find($location->salon_id);
        $booking_options = BookingPolicy::where('salon_id', $salon->id)->first();

        $staff_list = [];
        $dates_list = [];
        $dates_list_final = [];

        // if staff selection is enabled
        // get disabled days from user schedule
        if($booking_options->staff_selection === 0) {

            if($request->staff === 'any') {
                $dates_list = $this->salon_repo->getHiddenDays($location->id);
            } else {

                foreach($request->staff as $staff) {
                    $staff_list[] = $staff['staff'];
                }

                $staff = array_unique($staff_list);

                foreach($staff as $users) {
                    if($user = User::find($users)) {
                        $duration = '+3 month';

                        $dates = $this->staff_repo->createSchedule($user, $duration);

                        if($dates === 0) {
                            return ['status' => 0, 'message' => trans('salon.error_fetching_schedule')];
                        }

                        foreach($dates as $day) {
                            if(!$this->validateDate($day['timetable']['start'])) {
                                $dates_list[] = date('m-d-Y', strtotime($day['date']['date']));
                            }
                        }
                    }
                }
            }

            return ['status' => 1, 'disabled_dates' => $dates_list];

        // if staff selection is disabled
        // get salon working days and select random staff
        } else {

            $staff_all = [];
            $staff_randomly_selected = [];

            foreach($request->services as $service) {
                $service_string = explode('-', $service);
                $service_id = $service_string[1];
                $service = Service::find($service_id);

                foreach($service->service_staff as $service_staff) {
                    $staff[] = $service_staff->user_id;
                }

                $staff_id = array_rand($staff);
                $staff_randomly_selected[] = $staff[$staff_id];

            }

            $duration = '+3 month';

            foreach($staff_randomly_selected as $users) {
                if($user = User::find($users)) {
                    $duration = '+3 month';

                    $dates = $this->staff_repo->createSchedule($user, $duration);

                    //$disabled_days = $this->salon_repo->getHiddenDays($location->id);

                    if($dates === 0) {
                        return ['status' => 0, 'message' => trans('salon.error_fetching_schedule')];
                    }

                    foreach($dates as $day) {
                        if(!$this->validateDate($day['timetable']['start'])) {
                            $dates_list[] = date('m/d/Y', strtotime($day['date']['date']));
                        }
                    }
                }
            }

            return ['status' => 1, 'disabled_dates' => $dates_list, 'random_staff' => $staff_randomly_selected];

        }

        return ['status' => 1, 'disabled_dates' => $disabled_days];
    }

    public function getStaffHours(Request $request) {

        $location = Location::find($request->location);
        $salon = Salons::find($location->salon_id);
        $booking_options = BookingPolicy::where('salon_id', $salon->id)->first();
        $duration = '+3 month';
        $selected_date = date('Y-m-d', strtotime($request->selected_date));

        $schedule_list = [];
        $selected_days = [];
        if($request->staff != 'all' && count($request->staff) > 0) {
            foreach($request->staff as $staff_id) {
                if($user = User::find($staff_id)) {

                    $schedule = $this->staff_repo->createSchedule($user, $duration);

                    if($booking_options->multiple_staff != 0) {
                        $schedule_list[] = $schedule;
                    }

                }
            }

            if($booking_options->multiple_staff != 0) {
                foreach($schedule_list as $val) {
                    foreach($val as $sch) {
                        if($sch['date']['date'] === $selected_date) {
                            $selected_days[$sch['date']['user_id']] = $sch['timetable'];
                        }
                    }
                }

                $service_index = 0;
                $user_selection = [];
                $services_array = [];
                foreach($request->service as $service_selected) {
                    $service_array = explode('-', $service_selected);
                    $service_id = $service_array[1];

                    $user_selection[] = [
                        'service_id' => $service_id,
                        'employee_id' => $request->staff[$service_index]
                    ];
                    $service_obj = Service::find($service_id);

                    $services_array[$service_id] = [
                        'name' => $service_obj->service_details->name,
                        'duration' => $service_obj->service_details->service_length
                    ];

                    $service_index++;
                }

                $available_times_array = [];
                $start_time = date('H:i:s', strtotime($selected_days[$user_selection[0]['employee_id']]['start']));
                $end_time = date('H:i:s', strtotime($this->booking_repo->getMaxEndWorkHour($user_selection, $selected_days)));
                $selected_day = $this->booking_repo->getAvailableTimes($selected_date, $user_selection, $selected_days, $services_array, $start_time, $end_time, $available_times_array);

            } else {
                $selected_day = $this->booking_repo->getBookingSchedule($request->location, $user, $request->selected_date, $schedule, $request->service);
            }

            return $selected_day;

        } else {
            $user = null;
            $schedule = $this->staff_repo->getLocationSchedule($request->location, $duration);
            $selected_day = $this->booking_repo->getBookingSchedule($request->location, $user = null, $request->selected_date, $schedule, $request->service);

            return $selected_day;

        }

        return ['status' => 0, 'message' => trans('salon.user_not_found')];

    }

    public function addNewBooking(Request $request) {

        if($location = Location::find($request->booking_location)) {

            $salon = Salons::find($location->salon_id);
            $booking_options = BookingPolicy::where('salon_id', $salon->id)->first();

            $booking = $this->booking_repo->addNewBooking($location, $request->all(), $booking_options);

            return ['status' => $booking['status'], 'message' => $booking['message']];
        }

        return ['status' => 0, 'message' => trans('salon.booking_unknown_error')];

    }

    public function clientConfirmBooking(Request $request) {

        if($location = Location::find($request->booking_location)) {

            $salon = Salons::find($location->salon_id);
            $booking_options = BookingPolicy::where('salon_id', $salon->id)->first();

            $booking = $this->booking_repo->clientAddNewBooking($location, $request->all(), $booking_options);

            return $booking;

        }

        return ['status' => 0, 'message' => trans('salon.location_not_found')];

    }

    public function shareOnFacebook($booking_id, $unique_id) {
        try {

            $user = User::where('pin', $unique_id)->first();

            if($user != null) {
                Auth::login($user);

                $booking = Booking::find($booking_id);
                $salon = Salons::find($booking->booking_location->salon_id);
                $loyalty = LoyaltyManagement::where('salon_id', $salon->id)->first();
                $loyalty_program = LoyaltyPrograms::where('location_id', $booking->booking_location->id)->first();

                if($loyalty != null) {
                    $unique_url = isset($salon->unique_url) ? route('salonWebsite', $salon->unique_url) : null;
                    $title = $loyalty_program->share_title;
                    $desc = $loyalty_program->share_desc;

                    Session::put('facebook_share', 1);
                    Session::put('facebook_share_booking', $booking_id);
                    Session::put('social_points', $loyalty->social_points);
                    Session::put('url', $unique_url);
                    Session::put('title', $title);
                    Session::put('description', $desc);

                    return redirect()->route('clientAppointments')
                        ->with('success_message', trans('main.login_successful'));
                }
            }
            return redirect()->route('clientAppointments')
                ->with('error_message', trans('salon.unknown_error'));
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }


    }

    public function getBookingComplete() {

        return view('website.bookingComplete');

    }

    public function updateCustomStyles(Request $request) {

        if($custom_styles = CustomStyles::where('salon_id', Auth::user()->salon_id)->first()) {

            $update_styles = $this->booking_repo->updateCustomStyles($custom_styles, $request->all());

            if($update_styles['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.updated_successfuly'));
            }

        }

        return redirect()->back()->with('error_message', trans('salon.error_updating'));

    }

    public function rescheduleBooking(Request $request) {

        if($booking = Booking::find($request->id)) {

            $booking_update = $this->booking_repo->rescheduleBooking($booking, $request->all());

            if($booking_update['status'] === 1) {

                $this->booking_repo->checkWaitingList($booking->location_id);

                return ['status' => 1];
            }

            return ['status' => 0, 'message' => $booking_update['message']];

        }

        return ['status' => 0, 'message' => trans('salon.booking_update_failed')];

    }

    public function editBookingInfo(Request $request) {

        if($location = Location::find($request->booking_location)) {

            $booking_edit = $this->booking_repo->editBooking($location, $request->all());

            if($booking_edit['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.updated_successfuly'));
            }

        }

        return redirect()->back()->with('error_message', trans('salon.error_updating'));
    }

    public function getBookingInfo($id) {

        if($booking = Booking::find($id)) {

            $booking_details = BookingDetails::where('booking_id', $id)->first();

            return ['status' => 1, 'info' => $booking_details];
        }

        return ['status' => 0];

    }


    public function addClientNote(Request $request) {

        if($booking = Booking::find($request->booking_id)) {

            $customer = $booking->client;

            $customer_note = $this->booking_repo->addClientNote($customer, $request->customer_note);

            if($customer_note['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.updated_successfuly'));
            }

        }

        return redirect()->back()->with('error_updating', trans('salon.error_updating'));
    }

    public function getClients($location_id) {

        if($location = Location::find($location_id)) {
            $salon = Salons::find($location->salon_id);
            $clients = [];

            $client_list = Clients::where('location_id', $location->id)->get();
            foreach($client_list as $single_client) {
                $clients[] = [
                    'id' => $single_client->id,
                    'first_name' => $single_client->first_name,
                    'last_name' => $single_client->last_name
                ];
            }

            return ['status' => 1, 'message' => 1, 'clients' => $clients];
        }

        return ['status' => 0];

    }

    public function submitServices(Request $request) {

        $staff = $this->booking_repo->getStaff($request->all());

        if($staff['status'] === 1) {
            return $staff['staff'];
        }

        return ['status' => 0, 'message' => $staff['message']];

    }

    public function addNewClientInfo(Request $request) {

        $validator = Validator::make($request->all(), Clients::$client_validation_rules);
        $check_client = $this->client_repo->checkIfClientExists($request->all());

        if($validator->fails()) {
            return ['status' => 0, 'message' => $validator->errors()->all()[0]];
        }

        if($check_client['status'] === 0) {
            return ['status' => 0, 'message' => trans('salon.email_address_taken')];
        }

        if($location = Location::find($request->location)) {

            $client = $this->booking_repo->createNewClient($request->all());

            if($client['status'] === 1) {
                return ['status' => 1, 'client' => $client['client'], 'message' => $client['message']];
            }

            return ['status' => 0, 'message' => $client['message']];
        }

        return ['status' => 0, 'message' => trans('salon.error_updating')];

    }

    public function getClient($id) {

        if($client = Clients::find($id)) {

            if($client->location_id == Auth::user()->location_id) {

                return ['status' => 1, 'client' => $client];

            }

        }

        return ['status' => 0];

    }

    public function redeemCode(Request $request) {

        if ($code = Vouchers::where('code', $request->code)->first()) {

            $redeem_code = $this->booking_repo->redeemCode($request->all(), $code);

            if($redeem_code['status'] === 1) {
                $discount_applied_to = implode(', ', $redeem_code['discount_applied_to']);
                return ['status' => 1, 'price' => $redeem_code['price'], 'message' => trans('salon.code_applied', ['discount_applied_to' => $discount_applied_to])];
            }

            return ['status' => 0, 'price' => $redeem_code['message']];
        }

        return ['status' => 0, 'message' => trans('salon.code_not_found_or_expired')];

    }

    public function calculatePoints(Request $request) {

        $points = $this->booking_repo->calculatePoints($request->all());

        if($points['status'] === 1) {
            return ['status' => 1, 'points' => $points['points'], 'message' => trans('salon.booking_points_awarded', ['points' => $points['points']])];
        }

        return ['status' => 0];

    }

    public function waitingListReschedule($token, $date) {

        $booking = Booking::where('reschedule_token', $token)->first();

        if($booking != null) {

            $reschedule_waiting_list = $this->booking_repo->rescheduleWaitingList($booking, $date);

            if($reschedule_waiting_list['status'] === 1) {
                return redirect()->route('dashboard')->with('success_message', trans('salon.booking_rescheduled'));
            }

            return redirect()->route('dashboard')->with('error_message', trans('salon.reschedule_error'));

        }

        return redirect()->route('dashboard')->with('error_message', trans('salon.reschedule_error'));

    }

    public function getClientFields($location_id) {

        if($location = Location::find($location_id)) {

            $salon = Salons::find($location->salon_id);

            $fields = $this->booking_repo->getRequiredFields($salon->id, $location_id);

            if($fields['status'] === 1) {
                return ['status' => 1, 'fields' => $fields['fields']];
            }

            return ['status' => 0, 'message' => $fields['message']];

        }

        return ['status' => 0, 'message' => trans('salon.location_not_found')];

    }

    public function getBooking($id) {

        $booking = $this->booking_repo->getBooking($id);

        if($booking['status'] === 1) {
            return ['status' => 1, 'booking' => $booking];
        }

        return ['status' => 0, 'message' => $booking['message']];

    }

}
