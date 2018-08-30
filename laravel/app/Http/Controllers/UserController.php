<?php

namespace App\Http\Controllers;

use App\Repositories\CalendarRepository;
use Exception;
use App\{User,RoleRole};
use App\Models\Languages;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\{Validator,Auth};
use App\Notifications\EmailVerification;
use Socialite;
use Session;

class UserController extends Controller
{
    protected $repo;

    public function __construct() {
        $this->repo = new UserRepository;
        $this->calendar_repo = new CalendarRepository;
    }

    public function postRegister(Request $request) {

        if(!isset($request->type)) {
            if($request->consent === 1 || $request->consent === 'on') {

                Validator::make($request->all(), User::$user_rules)->validate();

                $language = $request->language;
                $first_name = $request->first_name;
                $last_name = $request->last_name;
                $email = $request->email;
                $password = $request->password;
                $gdpr = $request->consent;
                $salon = 1;
                $active = 0;

                $saveUser = $this->repo->registerUser($language, $first_name, $last_name, $email, $password, $salon, $active, $gdpr);

                if($saveUser['status'] === 1) {

                    $user = $saveUser['user'];

                    $user->notify(new EmailVerification($user->remember_token));

                    return redirect()->route('signin')->with('success_message', trans('auth.registration_success'));

                } else {
                    return $saveUser['message'];
                }
            }

            return redirect()->route('signin')->with('error_message', trans('salon.terms_not_accepted'));

        } else {
            if($request->consent === 1 || $request->consent === 'on') {
                $validator = Validator::make($request->all(), User::$user_rules);

                if ($validator->fails()) {
                    return ['status' => 0, 'message' => $validator->errors()->all()[0]];
                }

                $language = 2;
                $first_name = $request->first_name;
                $last_name = $request->last_name;
                $email = $request->email;
                $password = $request->password;
                $gdpr = $request->consent;
                $salon = 0;
                $active = 1;

                $saveUser = $this->repo->registerUser($language, $first_name, $last_name, $email, $password, $salon, $active, $gdpr);

                if ($saveUser['status'] === 1) {

                    if (Auth::attempt(array('email' => $request->email, 'password' => $request->password, 'email_verified' => 1))) {

                        $user = Auth::user();

                        $user_lang = $user->language;
                        $lang_iso = Languages::find($user_lang);

                        Session::put('language', $lang_iso->language_iso);

                        return ['status' => 1, 'message' => trans('auth.registration_success')];

                    }

                    return ['status' => 1, 'message' => trans('auth.login_error')];

                } else {
                    return ['status' => 0, 'message' => $saveUser['message']];
                }
            }
            return ['status' => 0, 'message' => trans('salon.terms_not_accepted')];
        }

    }

    public function saveSalonUser(Request $request)
    {
        $data["email"] = $request->input("email");
        
        $this->repo->createSalonUser($data);
    }

    public function verifyEmail($email_code){

        try {
            //select user from db with same remember_token
            $user = User::where('remember_token', $email_code)->first();

            if(!isset($user)) {

                return redirect()->route('signin')->with('error_message', trans('auth.verification_error'));

            } else if($user->email_verified === 1) {
                
                return redirect()->route('signin')->with('warning_message', trans('auth.email_already_verified'));
                
            }
            //reset remember_token and activate user

            $user->email_verified = 1;
            $user->save();

            return redirect()->route('signin')->with('success_message', trans('auth.verification_success'));

        } catch (Exception $exc) {

            return array('status' => 0, 'message' => $exc->getMessage());

        }

    }

    public function postLogin(Request $request) {
        //authenticate user if user is active
        if(!isset($request->type)) {
            if(Auth::attempt(array('email' => $request->email, 'password' => $request->password, 'email_verified' => 1))) {
                
                $user = Auth::user();
                
                $user_lang = $user->language;
                $lang_iso = Languages::find($user_lang);
                
                Session::put('language', $lang_iso->language_iso);

                if(Auth::user()->hasRole('user')) {
                    return redirect()->route('clientAppointments');
                }
                return redirect('dashboard');
                
            }
    
            return back()->withInput()->with('error_message', trans('auth.login_error'));
        } else {
            if(Auth::attempt(array('email' => $request->email, 'password' => $request->password, 'email_verified' => 1))) {
                
                $user = Auth::user();
                
                $user_lang = $user->language;
                $lang_iso = Languages::find($user_lang);
                
                Session::put('language', $lang_iso->language_iso);
    
                return ['status' => 1, trans('auth.login_success')];
                
            }
    
            return ['status' => 0, trans('auth.login_error')];
        }
    }

    public function logout() {
        
        Auth::logout();
        Session::flush();
        
        return redirect()->route('signin');
        
    }
    
    public function redirectToProvider($provider) {

        if($_GET['code']) {
            $cal = $this->calendar_repo->addEventsToCalendar($_GET['code']);

            if($cal['status'] === 1) {
                return redirect()->route('appointments')->with('success_message', $cal['message']);
            } else {
                return redirect()->route('appointments')->with('error_message', $cal['message']);
            }
        }

        return Socialite::driver($provider)->redirect();
        
    }
    
    public function handleProviderCallback($provider) {

        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);

        Auth::login($authUser, true);

        return redirect('dashboard');
        
    }
    
    public function findOrCreateUser($user, $provider) {
        
        $authUser = User::where($provider . '_id', $user->id)->first();

        if ($authUser != null) {
            return $authUser;
        } else {
            $authUser = User::where('email', $user->email)->first();
            if($authUser) {
                return $authUser;
            }
        }
        
        $saveUser = $this->repo->socialSignUp($user, $provider);
        
        return $saveUser;
        
    }
    
    public function addNewUser(Request $request) {
        
        if(Auth::user()->hasRole('superadmin') || Auth::user()->hasRole('alonadmin')) {
            $user_data = $request->all();
            $saveUser = $this->repo->createNewUser($user_data);

            if($saveUser['status'] === 1) {
                return ['status' => 1];
            }
        }
    }
    
    public function changePermission(Request $request) {
        $result = $this->repo->changeUserPermissions($request["roleID"],$request["permName"],$request["action"]);

        return ["status"=>1];
    }
    
    public function updateUserAccount(Request $request) {
        
        if($user = User::find($request->user_id)) {
            $update_user = $this->repo->updateAccount($user, $request->all());
            
            if($update_user['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.updated_successfuly'));
            }
        }
        
        return redirect()->back()->with('error_message', trans('salon.error_updating'));
        
    }
    
    public function updateUserPicture(Request $request) {
        
        if($user = User::find($request->user_id)) {
            
            $profile_picture = $this->repo->updateAvatar($user, $request->all());

            if($profile_picture['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.updated_successfuly'));
            }
        }
        
        return redirect()->back()->with('error_message', trans('salon.error_updating'));
        
    }
    
    public function submitPin(Request $request) {
        
        $user = Auth::user();
        if($user->pin === $request->pin) {

            $check_in = $this->repo->saveCheckIn($user, $request->pin);
            if($check_in['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.welcome'));
            }
        }
        
        return redirect()->back()->with('error_message', trans('salon.wrong_pin'));
    
    }

    public function deleteUserAccount(Request $request) {
        $account = $this->repo->deleteUserAccount($request->id);
        return ['status' => $account['status'], 'message' => $account['message']];
    }

}
