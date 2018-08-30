<?php

namespace App\Http\Controllers;

use App\Models\Salon\SalonPaymentOptions;
use Illuminate\Http\Request;
use App\Repositories\{SalonRepository,InfoRepository,StaffRepository,BookingRepository,ClientRepository};
use Illuminate\Support\Facades\{Validator,Auth};

use App\Models\{Salons,Location,StaffServices};
use App\Models\Salon\{Category,Group,SubCategory,Service,CustomFields};
use App\Models\Booking\{Booking,BookingFields,CustomStyles,BookingPolicy,BookingDetails,Clients,CalendarOptions,CalendarSettings};
use App\Models\Clients\{ClientLabels,ClientReferrals,ClientLocations};
use App\User;
use DateTime;

class ClientController extends Controller
{
    
    public function __construct() {
        $this->staff_repo = new StaffRepository;
        $this->booking_repo = new BookingRepository;
        $this->salon_repo = new SalonRepository;
        $this->client_repo = new ClientRepository;
    }
    
    public function getSalonClients() {

        $salon = Salons::find(Auth::user()->salon_id);
        
        $location = Auth::user()->location_id;

        $custom_fields = CustomFields::where('location_id', $location)->get();

        $clients = Clients::where('location_id', Auth::user()->location_id)->get();

        $custom_display_fields = CustomFields::where('location_id', Auth::user()->location_id)->where('field_location', 'clients')->get();

        $client_labels = ClientLabels::where('salon_id', 'all')->orWhere('salon_id', $salon->id)->get();
        $client_referrals = ClientReferrals::where('salon_id', $salon->id)->get();
        
        return view('booking.clients', ['salon' => $salon, 'clients' => $clients, 'location' => $location, 'booking_fields' => $custom_fields, 'client_labels' => $client_labels,
                                        'client_referrals' => $client_referrals, 'custom_fields' => $custom_display_fields]);
        
    }
    
    public function updateClientSettings(Request $request) {

        $client_settings = $this->client_repo->updateClientSettings($request->all());

        return ['status' => $client_settings['status'], 'message' => $client_settings['message']];
    }
    
    public function saveClientLabel(Request $request) {

        $client_label = $this->client_repo->saveClientLabel($request->all());

        if($client_label['status'] === 1) {
            return ['status' => 1, 'message' => $client_label['message'], 'label' => $client_label['label']];
        }

        return ['status' => 0, 'message' => $client_label['message']];

    }
    
    public function deleteClientLabel(Request $request) {
        
        if($label = ClientLabels::find($request->label)) {
            
            $label->delete();
            
            return ['status' => 1];
            
        }
        
        return ['status' => 0];
        
    }
    
    public function saveClientReferral(Request $request) {

        $client_referral = $this->client_repo->saveClientReferral($request->all());

        if($client_referral['status'] === 1) {
            return ['status' => 1, 'message' => $client_referral['message'], 'referral' => $client_referral['referral']];
        }

        return ['status' => 0, 'message' => $client_referral['message']];
        
    }
    
    public function deleteClientReferral(Request $request) {
        
        if($referral = ClientReferrals::find($request->referral)) {
            
            $referral->delete();
            
            return ['status' => 1];
            
        }
        
        return ['status' => 0];
        
    }
    
    public function setClientLabel(Request $request) {
        
        if($client = Clients::find($request->client)) {
            $client->label = $request->label;
            $client->save();
            
            return ['status' => 1];
        }
        
        return ['status' => 0];
        
    }
    
    public function setClientReferral(Request $request) {
        
        if($client = Clients::find($request->client)) {
            $client->referral = $request->referral;
            $client->save();
            
            return ['status' => 1];
        }
        
        return ['status' => 0];
        
    }
    
    public function getClientProfile($id) {
        
        $client_info = Clients::find($id);
        
        $booking_fields = CustomFields::where('location_id', Auth::user()->location_id)->get();

        $client_booking_list = Booking::where('client_id', $id)->where('location_id', Auth::user()->location_id)->get();
        
        return view('salon.clientProfile', ['client' => $client_info, 'booking_fields' => $booking_fields, 'client_bookings' => $client_booking_list]);
        
    }
    
    public function updateClientInfo(Request $request) {

        $client_info = $this->client_repo->updateClientInfo($request->all());
            
        return ['status' => $client_info['status'], 'message' => $client_info['message']];
        
    }

    public function getClientAppointments() {

        if($user = Auth::user()) {

            $appointments = $this->client_repo->getClientAppointments($user);

            if($appointments['status'] === 1) {

                $appointments_stats = $this->client_repo->getAppointmentStats($appointments['booking_list']);

                return view('clients.clientAppointments', ['bookings' => $appointments['booking_list'], 'stats' => $appointments_stats]);
            }

            return redirect()->back()->with('error_message', $appointments['message']);

        }

    }

    public function getPrivacySettings() {

        if($user = Auth::user()) {

            return view('clients.privacySettings', ['user' => $user]);

        }

        return redirect()->back()->with('error_message', trans('salon.unknown_error'));

    }

    public function getUpcomingBookings(Request $request) {

        $upcoming_bookings = $this->client_repo->getUpcomingBookings($request->all());

        if($upcoming_bookings['status'] === 1) {

            return ['status' => 1, 'upcoming_bookings' => $upcoming_bookings['booking_list']];
        }

        return ['status' => 0];

    }

    public function updatePrivacySettings(Request $request) {

        $privacy = $this->client_repo->updatePrivacySettings($request->all());

        return ['status' => $privacy['status'], 'message' => $privacy['message']];

    }

    public function getLoyaltyStatus() {

        $loyalty_status = $this->client_repo->getClientLoyalty();

        if($loyalty_status['status'] === 1) {
            return view('clients.loyaltyPoints', ['loyalty' => $loyalty_status['loyalty']]);
        }

        return redirect()->back()->with('error_message', $loyalty_status['message']);

    }
}
