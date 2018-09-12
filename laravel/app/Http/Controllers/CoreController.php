<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\{InfoRepository,UserRepository,SalonRepository,StaffRepository};
use App\Models\Salons;
use App\Models\{Languages,Location,Countries};
use Illuminate\Support\Facades\{Session,DB,Auth,Input};
use App;
use App\{User,Role,Permission,PermissionRole};
use App\Notifications\{WaitingListNotification,NewClient};

use App\Models\Marketing\Reminders;
use App\Models\Booking\Booking;
use Spatie\GoogleCalendar\Event;

class CoreController extends Controller
{
    protected $info_repo;
    protected $user_repo;
    protected $salon_repo;
    private $test;
    
    public function __construct() {
        
        $this->info_repo = new InfoRepository;
        $this->user_repo = new UserRepository;
        $this->salon_repo = new SalonRepository;
        $this->staff_repo = new StaffRepository;
        $this->booking_repo = new App\Repositories\BookingRepository;
        $this->client_repo = new App\Repositories\ClientRepository;
        $this->marketing_repo = new App\Repositories\MarketingRepository;
        $this->pos_repo = new App\Repositories\PosRepository;
    }
    
    public function addPermission() {

        /*$perm = new Permission();
        $perm->name = 'manage-salon';
        $perm->display_name = 'Manage salon';
        $perm->description = 'Can edit salon settings';
        $perm->save();

        $perm2 = new Permission();
        $perm2->name = 'manage-locations';
        $perm2->display_name = 'Manage locations';
        $perm2->description = 'Can edit location settings and info';
        $perm2->save();

        $perm3 = new Permission();
        $perm3->name = 'manage-staff';
        $perm3->display_name = 'Manage staff';
        $perm3->description = 'Can manage staff';
        $perm3->save();

        $perm4 = new Permission();
        $perm4->name = 'view-rosters';
        $perm4->display_name = 'View rosters';
        $perm4->description = 'Can view staff rosters';
        $perm4->save();

        $perm5 = new Permission();
        $perm5->name = 'add-vacations';
        $perm5->display_name = 'Add vacations';
        $perm5->description = 'Can add staff vacations';
        $perm5->save();

        $perm6 = new Permission();
        $perm6->name = 'manage-booking';
        $perm6->display_name = 'Manage booking';
        $perm6->description = 'Can edit booking settings';
        $perm6->save();

        $perm7 = new Permission();
        $perm7->name = 'view-appointments';
        $perm7->display_name = 'View appointments';
        $perm7->description = 'Can view appointments';
        $perm7->save();

        $perm8 = new Permission();
        $perm8->name = 'manage-calendar';
        $perm8->display_name = 'Manage calendar';
        $perm8->description = 'Can manage calendar';
        $perm8->save();

        $perm9 = new Permission();
        $perm9->name = 'manage-clients';
        $perm9->display_name = 'Manage clients';
        $perm9->description = 'Can manage clients';
        $perm9->save();

        $perm10 = new Permission();
        $perm10->name = 'manage-website';
        $perm10->display_name = 'Manage website';
        $perm10->description = 'Can manage website';
        $perm10->save();

        $perm11 = new Permission();
        $perm11->name = 'manage-loyalty';
        $perm11->display_name = 'Manage loyalty';
        $perm11->description = 'Can manage loyalty';
        $perm11->save();

        $perm12 = new Permission();
        $perm12->name = 'manage-marketing';
        $perm12->display_name = 'Manage marketing';
        $perm12->description = 'Can manage marketing';
        $perm12->save();

        $perm13 = new Permission();
        $perm13->name = 'manage-pos';
        $perm13->display_name = 'Manage POS';
        $perm13->description = 'Can edit POS settings';
        $perm13->save();
        */
    }

    public function getSignIn() {
    
        if(Auth::user()) {
            
            return redirect()->route('dashboard');
    
        }
        
        return view('auth.login');
        
    }

    public function getSignUp() {

        $country_list  = $this->info_repo->getCountryList();
        $language_list = $this->info_repo->getLanguageList();

        return view('auth.register', ['country_list' => $country_list, 'language_list' => $language_list]);
        
    }
    
    public function switchLanguage($id) {
        
        $language_list = Languages::all();
        
        if(!isset($language_list)) {
            return redirect()->route('dashboard')->with('error_message', trans('errors.error_fetching_language'));
        }
        
        foreach($language_list as $language) {
            
            if($language['id'] == $id) {
                
                $update_lang = $this->user_repo->updateUserLanguage($id);
                
                if($update_lang['status'] == 1) {
                    
                    Session::put('language', $language->language_iso);
                    
                    return redirect()->back();
                    
                }
                
                return ['status' => 0];
                
            }
            
        }
        
    }

    public function getDashboard() {
        $user = Auth::user();

        $country_list = Countries::all();
        $month_list = $this->info_repo->getMonthList();

        if(!Auth::user()->hasRole('user')) {
            $salon = Salons::find($user->salon_id);
            $location = Location::find($user->location_id);

            if($location != null && $location->booking->isNotEmpty()) {

                $stats_date = $this->pos_repo->getStatsDate($location);
                if($stats_date['status'] === 1) {
                    $monthly_bookings = $this->booking_repo->getMonthlyBookings($stats_date['starts_date'], $month_list);

                    $month_list_complete = $monthly_bookings['month_list'];
                }

                $next_booking = $this->booking_repo->getNextBooking();
                $today_bookings = $this->booking_repo->getTodaysBookings();
                $today_bookings_amount = count($today_bookings['bookings']);

                $new_clients = $this->client_repo->getNewClients();

                if($monthly_bookings['status'] === 1) {
                    $monthly_booking_stats = $monthly_bookings['bookings'];
                } else {
                    $monthly_booking_stats = null;
                }

                return view('core.dashboard', ['salon' => $salon, 'countries' => $country_list, 'monthly_bookings' => $monthly_booking_stats,
                                                     'new_clients' => $new_clients['clients'][0], 'next_booking' => $next_booking['booking'], 'today_bookings' => $today_bookings_amount,
                                                     'month_list' => $month_list_complete, 'stats_date' => $stats_date['starts_date']]);
            }

            return view('core.dashboard', ['salon' => $salon, 'countries' => $country_list, 'monthly_bookings' => 'undefined']);
        }

        return redirect()->route('clientAppointments');
        
    }

    public function changeStatsDate(Request $request) {

        $stats_date = $this->pos_repo->changeStatsDate($request->all());

        return ['status' => $stats_date['status'], 'message' => $stats_date['message']];
    }
    
    public function getSalonUserManagement() {
        
        $users = $this->user_repo->getUsers();
        $user_roles = $this->info_repo->getUserRoles();
        $permissions = Permission::all();
        $permissionData = array();

        foreach($permissions as $perm) {
            
            $roles = Role::where("id", "!=", 1)->where("id", "!=", 8)->get();

            foreach($roles as $role) {
                
                $checkPerm = PermissionRole::where("permission_id", $perm->id)->where("role_id", $role->id)->first();
                
                if($checkPerm) {
                    
                    $permissionData[$perm->name][$role->name]["selected"] = true;
                    
                    $permissionData[$perm->name][$role->name]["id"] = $role->id;
                    
                } else {
                    
                    $permissionData[$perm->name][$role->name]["selected"] = false;
                    
                    $permissionData[$perm->name][$role->name]["id"] = $role->id;
                    
                }
            }
        }
        
        return view('salon.salonUserManagement', ['users' => $users, 'user_roles' => $user_roles,'permissions'=>$permissionData]);
        
    }
    
    public function getAccountInfo() {
        
        return view('core.userAccountSettings', ['user' => Auth::user()]);
        
    }
    
    public function deleteUser($id) {
        
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('salonadmin')) {
            try {
                $user = User::where('id', $id)->first();
                if(Auth::user()->hasRole('salonadmin') && Auth::user()->salon_id == $user->salon_id) {
                    $user->delete();
                } else if(Auth::user()->hasRole('superadmin')) {
                    $user->delete();
                }
                return ['status' => 1];
            } catch (Exception $exc) {
                return ['status' => 0, 'message' => $exc->getMessage()];
            }
        }
        
        return ['status' => 0];
    }
    
    public function signInAsUser($id) {
        
        $user = User::find($id);
        
        if($user) {
            try {
                Auth::login($user);
                Session::put('super_admin', 1);
                return redirect()->route('dashboard')->with('success_message', trans('main.login_successful'));
            } catch (Exception $exc) {
                return redirect()->back()->with('error_message', $exc->getMessage());
            }
            
        }
        
        return ['status' => 0];
        
    }

    public function signInAsAdmin($id) {
        $salon = Salons::find($id);
        $users = User::where('salon_id', $salon->id)->get();
        foreach($users as $user) {
            if($user->hasRole('salonadmin')) {
                $salon_admin = $user;
            }
        }

        if($salon_admin) {
            try {
                Auth::login($salon_admin);
                Session::put('super_admin', 1);
                return redirect()->route('dashboard')->with('success_message', trans('main.login_successful'));
            } catch (Exception $exc) {
                return redirect()->back()->with('error_message', $exc->getMessage());
            }

        }
    }
    
    public function forgotPassword() {
        return view('auth.passwords.email');
    }
    
    public function adminSwitchLocation(Request $request) {
        if($location = Location::find($request->location)) {
            $switch_location = $this->staff_repo->assignLocationToAdmin($request->location);
            
            if($switch_location['status'] === 1) {
                return ['status' => 1];
            }
        }
        return ['status' => 0];
    }

    public function deleteSalon(Request $request) {
        $salon_delete = $this->salon_repo->deleteSalon($request->id);

        return ['status' => $salon_delete['status'], 'message' => $salon_delete['message']];
    }

    public function getPrivacyPolicyAPP() {
        return view('gdpr.privacyPolicyAPP');
    }

    public function getPrivacyPolicy($unique_url) {
        $salon = Salons::where('unique_url', $unique_url)->first();

        if($salon != null) {

            App::setLocale($salon->country);

            $website_content = App\Models\Website\WebsiteContent::where('salon_id', $salon->id)->first();

            $location_markers = [];

            $blog_posts = App\Models\Salon\BlogPost::where('salon_id', $salon->id)->take(4)->orderBy('id', 'DESC')->get();

            foreach($salon->locations as $location) {
                $location_markers[] = [
                    'location_name' => $location->location_name,
                    'address' => $location->address,
                    'city' => $location->city,
                    'phone' => $location->business_phone,
                    'email' => $location->email_address,
                    'lat' => $location->lat,
                    'lng' => $location->lng,
                    'unique_url' => $location->unique_url
                ];
            }

            return view('gdpr.privacyPolicy', ['salon' => $salon, 'website_content' => $website_content, 'location_markers' => $location_markers, 'latest_news' => $blog_posts]);

        }
        return view('website.404', ['message' => trans('salon.website_not_found')]);
    }

}
