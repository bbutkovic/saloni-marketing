<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator,Auth};

use App\Repositories\{BookingRepository,CalendarRepository,SalonRepository,InfoRepository};
use App\Notifications\WaitingListNotification;

use App\Models\Salon\Category;
use App\Models\{Salons,Location,SalonService};
use App\Models\Booking\{CalendarSettings,CalendarColors,CalendarOptions,Booking,BookingPolicy,BookingFields,DiscountCodes};
use App\User;
use App;

class CalendarController extends Controller
{
    protected $booking_repo;
    protected $calendar_repo;
    
    public function __construct() {
        $this->booking_repo = new BookingRepository;
        $this->calendar_repo = new CalendarRepository;
        $this->salon_repo = new SalonRepository;
        $this->info_repo = new InfoRepository;
    }
    
    public function getAppointments($staff_id = null) {
        
        $salon = Salons::find(Auth::user()->salon_id);

        $calendar_settings = CalendarSettings::where('salon_id', $salon->id)->first();
        $calendar_options = CalendarOptions::where('salon_id', $salon->id)->first();
        $calendar_colors = CalendarColors::where('salon_id', $salon->id)->first();
        $booking_options = BookingPolicy::where('salon_id', $salon->id)->first();
        $booking_fields = BookingFields::where('salon_id', $salon->id)->where('field_status', 1)->get();
        $category_list = Category::where('location_id', Auth::user()->location_id)->get();
        $hour_list = $this->info_repo->getHoursList($salon->id);

        $custom_fields = [];
        foreach($booking_fields as $field) {
            if($field->field_name === 'custom_field_1' || $field->field_name === 'custom_field_2' || $field->field_name === 'custom_field_3' || $field->field_name === 'custom_field_4') {
                $custom_fields[] = $field;
            }
        }

        if($booking_options != null && $calendar_settings != null && $calendar_options != null && $calendar_colors != null) {

            $selected_location = Location::find(Auth::user()->location_id);

            $locale = $selected_location->country;

            $staff_list = User::where('location_id', $selected_location->id)->get();

            if($staff_id != null) {
                $booking_list = User::where('id', $staff_id)->get();
            } else {
                $booking_list = $staff_list;
            }

            $bookings = $this->calendar_repo->getBookings($booking_list, $salon);

            if($bookings['status'] != 1) {
                return redirect()->back()->with('error_message', $bookings['message']);
            }

            $location_hours = $this->salon_repo->getHours($selected_location->id);

            $hidden_days = $this->salon_repo->getHiddenDays($selected_location->id);

            return view('calendar.appointments',
                ['locale' => $locale, 'selected_staff' => $staff_id, 'staff_list' => $staff_list, 'bookings' => $bookings['booking_list'], 'salon' => $salon, 'time_list' => $hour_list,
                    'calendar_settings' => $calendar_settings, 'calendar_options' => $calendar_options, 'calendar_colors' => $calendar_colors, 'location_hours' => $location_hours,
                    'hidden_days' => $hidden_days, 'booking_options' => $booking_options, 'booking_fields' => $booking_fields, 'custom_fields' => $custom_fields, 'category_list' => $category_list]);

        } else {
            return redirect()->route('calendarSettings')->with('error_message', trans('salon.settings_not_complete'));
        }

    }
    
    public function getCalendarSettings() {
        
        $salon = Salons::find(Auth::user()->salon_id);
        
        $calendar_settings = CalendarSettings::where('salon_id', $salon->id)->first();
        
        $calendar_options = $salon->calendar_options;

        $colors = CalendarColors::where('salon_id', $salon->id)->first();
        
        return view('calendar.calendarSettings', ['calendar_settings' => $calendar_settings, 'colors' => $colors, 'calendar_options' => $calendar_options]);
        
    }
    
    public function updateCalendar(Request $request) {

        $settings = $this->calendar_repo->updateCalendar($request->all());
        
        return ['status' => $settings['status'], 'message' => $settings['message']];
    }
    
    public function updateCalendarColors(Request $request) {

        $colors = $this->calendar_repo->updateColors($request->all());

        return ['status' => $colors['status'], 'message' => $colors['message']];
        
    }
    
    public function updateBookingStatus(Request $request) {

        $booking_status = $this->calendar_repo->updateBookingStatus($request->all());
        $salon = Salons::find(Auth::user()->salon_id);

        if($booking_status['status'] === 1) {

            $booking_list = User::where('location_id', Auth::user()->location_id)->get();

            $bookings = $this->calendar_repo->getBookings($booking_list, $salon);

            if($bookings['status'] != 1) {
                return ['status' => 0, 'message' => $bookings['message']];
            }

            return ['status' => 1, 'message' => $booking_status['message'], 'events' => $bookings['booking_list']];
        }
        return ['status' => 0, 'message' => $booking_status['message']];

    }

    public function getExportToCal() {

        $client = new \Google_Client();
        $client->setAuthConfig('client_secret.json');
        $client->addScope("https://www.googleapis.com/auth/calendar");

        $auth_url = $client->createAuthUrl();
        header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));

        return redirect($auth_url);

    }

    public function getCalendarLinks($booking_id) {

        $links = $this->calendar_repo->getCalendarLinks($booking_id);

        if($links['status'] === 1) {
            return ['status' => 1, 'links' => $links['calendar_links']];
        }

        return ['status' => 0, 'message' => $links['message']];

    }
    
}
