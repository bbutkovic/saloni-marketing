<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Session,Validator,Auth,Route};
use App\Repositories\{SalonRepository,UserRepository,InfoRepository,StaffRepository};
use App\Models\{Salons,Location,LocalHours};
use App\Models\Salon\{ScheduleChanges,StaffHours,Service,ServiceStaff};
use App\Models\Booking\Booking;
use App\{Role,User,Permission,PermissionRole};

class StaffController extends Controller {
    
    protected $salon_repo;
    protected $user_repo;
    protected $info_repo;
    
    public function __construct() {
        $this->salon_repo = new SalonRepository;
        $this->staff_repo = new StaffRepository;
        $this->info_repo = new InfoRepository;
    }
    
    public function getStaffSettings() {
        
        $salon = Salons::find(Auth::user()->salon_id);

        $week = $this->info_repo->getWeekDays();

        $hours = $this->info_repo->getHoursList($salon->id);
        
        return view('staff.staffGeneralSettings', ['salon' => $salon, 'week' => $week, 'hours' => $hours]);
        
    }
    
    public function getStaffManagement() {
        
        $salon_id = Auth::user()->salon_id;
        $locations = $this->info_repo->getLocationList($salon_id);
        
        if($locations['status'] != 0) {
        
            $users = User::where('location_id', Auth::user()->location_id)->get();
            $user_roles = $this->info_repo->getUserRoles();

            return view('staff.staffManagement', ['employees' => $users, 'user_roles' => $user_roles, 'locations' => $locations['location_list']]);
            
        }
        
        return redirect()->back()->with('error_message', trans('salon.no_locations_added'));
        
    }
    
    public function getSecurityManagement() {
        
        $permissions = Permission::all();
        $permissionData = array();

        foreach($permissions as $perm) {
            $roles = Role::where("id","!=",1)->where("id","!=",4)->get();
            foreach($roles as $role) {
                $checkPerm = PermissionRole::where("permission_id","=",$perm->id)->where("role_id","=",$role->id)->first();
                
                if($checkPerm) {
                    $permissionData[$perm->name][$role->name]["selected"] = true;
                    $permissionData[$perm->name][$role->name]["id"] = $role->id;
                } else {
                    $permissionData[$perm->name][$role->name]["selected"] = false;
                    $permissionData[$perm->name][$role->name]["id"] = $role->id;
                }
            }
        }
        
        return view('staff.staffPermissions', ['permissions' => $permissionData]);
    }
    
    public function getRosterManagement() {
        
        $salon_id = Auth::user()->salon_id;
        
        $locations = Location::where('salon_id', $salon_id)->get();
        
        if($locations->isNotEmpty()) {
            
            $staff = User::where('location_id', Auth::user()->location_id)->get();
            
            $days = $this->staff_repo->getCalendarDays();
            
            $weekly_roster = $this->staff_repo->getWeeklySchedule($staff);

            $week_dates = $this->info_repo->getWeekDates($salon_id);

            return view('staff.staffRosters', ['staff_roster' => $weekly_roster, 'user_list' => $staff, 'days' => $days, 'week_dates' => $week_dates]);
        }
        
        return redirect()->back()->with('error_message', trans('salon.no_locations_added'));
        
    }
    
    public function addNewStaff(Request $request) {

        $validator = Validator::make($request->all(), User::$staff_rules);
        
        if ($validator->fails()) {
            return ['status' => 0, 'error_message' => $validator->errors()->all()[0]];
        }
        
        $staff = $this->staff_repo->addNewMember($request->all());
        
        if($staff['status'] === 1) {
            return ['status' => 1];
        }
        
        return ['status' => 0];
    }
    
    public function viewProfile($id) {

        if($user = User::find($id)) {
            
            if($user->salon_id == Auth::user()->salon_id) {

                $locations = $this->info_repo->getLocationList($user->salon_id);
                
                $user_roles = Role::where('id', '!=', 1)->where('id', '!=', 8)->get();
                
                if(!$user->staff_hours->isEmpty()) {
                    $employee_hours = $this->staff_repo->createSchedule($user);

                    $message = '';

                    $countLastEl = count($employee_hours);
                    foreach($employee_hours as $hours) {
                        if(0 === --$countLastEl) {
                            $lastDate = $hours['date']['date'];
                        }
                    }

                } else {
                    $message = '';
                    $employee_hours = null;
                    $lastDate = null;
                }

                $week = $this->info_repo->getWeekDays();
        
                $time_list = $this->info_repo->getHoursList($user->salon_id);
                
                $services = Service::where('location_id', $user->location_id)->get();
                $service_staff = ServiceStaff::where('user_id', $user->id)->get();
                
                return view('staff.staffProfile', 
                    ['employee' => $user, 'user_roles' => $user_roles, 'locations' => $locations['location_list'], 
                    'week' => $week, 'time_list' => $time_list, 'staff_hours' => $employee_hours, 
                    'last_date' => $lastDate, 'message' => $message, 'services' => $services, 'service_staff' => $service_staff]);
                
            }
            
        }
        
        return redirect()->back()->with('error_message', trans('auth.user_not_found'));

    }
    
    public function updateUserProfile(Request $request) {

        $user_profile = $this->staff_repo->updateProfile($request->all());

        return ['status' => $user_profile['status'], 'message' => $user_profile['message']];
    }
    
    public function updateUserSecurity(Request $request) {
        
        if($request->password != '' && $request->password_confirmation != '') {
            Validator::make($request->all(), User::$user_security)->validate();
        }
        
        if(isset($request->email) && $request->email != null) {
            Validator::make($request->all(), User::$user_email)->validate();
        }
        
        if($user = User::find($request->user_id)) {

            $security = $this->staff_repo->updateSecurity($request->all(), $user);
            
            return ['status' => $security['status'], 'message' => $security['message']];
            
        }
        
        return ['status' => 0, 'message' => trans('auth.user_not_found')];
    }
    
    /*public function updateUserServices(Request $request) {

        $user_services = $this->staff_repo->updateServices($request->all());

        if($user_services['status'] === 0) {
            return redirect()->back()->with('error_message', $user_profile['message']);
        }
            
        return redirect()->back()->with('success_message', trans('salon.updated_successfuly'));
        
    }*/
    
    public function changeBookingStatus($uid, $val) {
        
        if($user = User::find($uid)) {
            if($user->salon_id == Auth::user()->salon_id) {
                if($val == 1) {
                    $user->user_extras->available_booking = 1;
                    $user->user_extras->save();
                } else {
                    $user->user_extras->available_booking = 0;
                    $user->user_extras->save();
                }
                return ['status' => 1];
            }
        }
        
        return ['status' => 0];
    }
    
    public function updateStaffSettings(Request $request) {

        $staff_settings = $this->staff_repo->updateStaffSettings($request->all());
        
        return ['status' => $staff_settings['status'], 'message' => $staff_settings['message']];
    }
    
    public function setStaffHours(Request $request) {

        if($user = User::find($request->uid)) {
            
            $staff_hours = $this->staff_repo->saveSchedule($user, $request->all());

            if($staff_hours['status'] === 1) {

                return ['status' => 1, 'message' => trans('salon.schedule_updated')];
                
            } else {
                
                return ['status' => 0, 'message' => $staff_hours['message']];
                
            }
        }
        
        return redirect()->back()->with('error_message', trans('auth.user_not_found'));
    }
    
    public function updateRole($uid, $role) {
        
        if($user = User::find($uid)) {
                
            if($user->salon_id == Auth::user()->salon_id) {
            
                $user_role = $user->roles[0];
                $user->detachRole($user_role);
                if($user->attachRole($role)) {
                    return ['status' => 1];
                }
            }
        }
        
        return ['status' => 0];
    }
    
    public function updateUserLocation($uid, $location_id) {
        
        if($user = User::find($uid)) {
            
            if($user->salon_id == Auth::user()->salon_id) {
            
                if($location = $this->staff_repo->updateUserLocation($user, $location_id)) {
                    return ['status' => 1];
                }
            }
            
        }
        
        return ['status' => 0];
    }
    
    public function updateUserSchedule(Request $request) {

        $user_obj = User::find($request->uid);
        
        $update_schedule = $this->staff_repo->updateSchedule($request->all());
        
        if($update_schedule['status'] === 0 && isset($update_schedule['bookings'])) {
            //return ['status' => 0, 'message' => $update_schedule['message'], 'bookings' => $update_schedule['bookings']];
            return ['status' => 0, 'message' => $update_schedule['message'], 'bookings' => 1];
        }
        
        if($update_schedule['status'] === 1) {
            return ['status' => 1, 'days' => $update_schedule['dates']];
        }
        
        return ['status' => 0];
        
    }
    
    public function deleteSchedule($id) {
        
        if($user = User::find($id)) {
            
            if($user->salon_id == Auth::user()->salon_id) {
                
                $check_user_bookings = Booking::where('staff_id', $user->id)->get();
                
                if($check_user_bookings->isNotEmpty()) {
                    return ['status' => 0, 'bookings' => 1];
                }
                
                $sch_delete = $this->staff_repo->deleteWeeklySchedule($user);
    
                if($sch_delete['status'] === 1) {
                    
                    return ['status' => 1];
                    
                }
                
            }
            
        }
        
        return ['status' => 0];
        
    }
    
    public function confirmScheduleDelete(Request $request) {
        
        $user = User::find($request->id);
        
        $sch_delete = $this->staff_repo->deleteWeeklySchedule($user);
        
        if($sch_delete['status'] === 1) {
                    
            return ['status' => 1];
            
        }
        
    }
    
    public function getLocationHours($id) {

        if($location = Location::find($id)) {
            
            $hours = LocalHours::where('location_id', $id)->get();
            
            return ['status' => 1, 'hours' => $hours];
        }
        
        return ['status' => 0];
        
    }
    
    public function addStaffVacation(Request $request) {
        
        if($user = User::find($request->select_staff)) {
            $vacation = $this->staff_repo->addVacation($request->all());
            
            if($vacation['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.updated_successfuly'));
            }
            
            return redirect()->back()->with('error_message', $vacation['message']);
            
        }
        
        return redirect()->back()->with('error_message', trans('auth.user_not_found'));
    }
    
    public function deleteVacation($id) {
        
        $vacation = $this->staff_repo->deleteVacation($id);
        
        if($vacation->staff->salon_id == Auth::user()->salon_id) {
        
            if($vacation['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.vacation_deleted'));
            }
            
        }
        
        return redirect()->back()->with('error_message', trans('salon.delete_failed'));
        
    }
    
    public function getWeeklySchedule($week_start = 0) {
        
        $schedule = $this->staff_repo->getWeeklySchedule($week_start);
        
        return $schedule;
        
    }
    
    public function addServicesToStaff(Request $request) {
        
        if($staff = User::find($request->uid)) {
            
            $services_update = $this->staff_repo->addServicesToStaff($staff, $request->all());

            if($services_update['status'] === 1) {
                return ['status' => 1, 'message' => trans('salon.updated_successfully')];
            }
            
            return ['status' => 0, 'message' => $services_update['message']];
            
        }
        
        return ['status' => 0, 'message' => trans('salon.error_updating')];
        
    }
    
}
