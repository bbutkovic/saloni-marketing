<?php

namespace App\Repositories;

use App\Models\Salons;
use App\Models\Users\UserExtras;
use Illuminate\Support\Facades\{Auth,Validator,Hash};
use App\Notifications\{EmailVerification,CreatedAccountVerify};
use App\{
    Models\Booking\Clients, User, Permission, Role, PermissionRole
};

use File,DB,Image;

class UserRepository {
    
    public function registerUser($language, $first_name, $last_name, $email, $password, $salon, $active, $gdpr){
        try {
            DB::beginTransaction();
            $user = new User;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->language = $language;
            $user->email_verified = $active;
            $user->remember_token = substr(md5(rand()), 0, 40);
            $user->gdpr_consent = $gdpr;
            $user->save();

            if($salon === 1) {
                $user->attachRole(2);
            } else {
                $user->attachRole(7);
            }

            $user_id = $user->id;

            $user_extras = new UserExtras;
            $user_extras->user_id = $user_id;
            $user_extras->first_name = $first_name;
            $user_extras->last_name = $last_name;
            $user_extras->photo = '/images/user_placeholder.png';
            $user_extras->save();
            DB::commit();

            $clients = Clients::where('email', $email)->get();
            if($clients->isNotEmpty()) {
                foreach($clients as $client) {
                    $client->user_id = $user->id;
                    $client->save();
                }
            }
            
            return array('status' => 1, 'user' => $user);
            
        } catch (Exception $exc) {
            
            return array('status' => 0, 'message' => $exc->getMessage());
            
        }
    }
    
    //customer only
    public function socialSignUp($user, $provider) {

        try {
            
            $new_user = new User;
            if($provider === 'facebook') {
                $new_user->facebook_id = $user->id;
                $fb_name = explode(' ', $user->name);
                $first_name = $fb_name[0];
                $last_name = $fb_name[1];
            } else {
                $new_user->google_id = $user->id;
                $first_name = $user->user['name']['givenName'];
                $last_name = $user->user['name']['familyName'];
            }
            $new_user->email = $user->email;
            $new_user->password = Hash::make($user->id);
            $new_user->language = 1;
            $new_user->email_verified = 1;
            $new_user->save();
            
            $new_user->attachRole(7);
            $user_id = $new_user->id;
            
            //user image
            $fileContents = file_get_contents($user->getAvatar());
            File::put(public_path() . '/images/profile/' . $user->getId() . ".jpg", $fileContents);
            
            $user_extras = new UserExtras;
            $user_extras->user_id = $user_id;
            $user_extras->first_name = $first_name;
            $user_extras->last_name = $last_name;
            $user_extras->photo = '/images/profile/' . $user->getId() . ".jpg";
            $user_extras->save();
            
            return $new_user;
            
        } catch(Exception $exc) {
            
            return array('status' => 0, 'message' => $exc->getMessage());
            
        }
        
    }
    
    public function getUsers() {
       
        $users = User::all();
        
        return $users;
        
    }
    
    public function updateUserLanguage($id) {
        
        $user = Auth::user();
        $user->language = $id;
        $user->save();
    
        return ['status' => 1];
        
    }
    
    //superadmin, salonadmin action
    public function createNewUser($user_data) {
        
        Validator::make($user_data, User::$user_rules_administration)->validate();
        
        try {
            
            DB::beginTransaction();
            
            $user = new User;
            $user->email = $user_data['user_email'];
            $user->password = Hash::make($user_data['password']);
            $user->language = 1;
            $user->email_verified = 0;
            $user->remember_token = substr(md5(rand()), 0, 40);
            $user->save();
            
            $user->attachRole($user_data['user_role']);
            $user_id = $user->id;
            $user->salon_id = $user_id;
            $user->save();
            
            $user_extras = new UserExtras;
            $user_extras->user_id = $user_id;
            $user_extras->first_name = $user_data['first_name'];
            $user_extras->last_name = $user_data['last_name'];
            $user_extras->photo = '/images/user_placeholder.png';
            $user_extras->save();

            //check if clients with this email address exist - if true add user id to clients
            $clients = Clients::where('email', $user_data['user_email'])->get();
            if($clients->isNotEmpty()) {
                foreach($clients as $client) {
                    $client->user_id = $user->id;
                    $client->save();
                }
            }
            
            DB::commit();
        
            $user->notify(new CreatedAccountVerify($user->remember_token, $user_data['password']));

        } catch (Exception $exc) {
            return redirect()->back()->with('error_message', trans('auth.registration_success'));
        }
        
        return ['status' => 1];
        
    }
    
    public function changeUserPermissions($role_id, $permission_name, $action) {
        
        $role = Role::find($role_id);
        $permission = Permission::where("name", $permission_name)->first();
        
        if($action == 'attach') {
            $role->attachPermission($permission->id);
        } else {
            $role->detachPermission($permission->id);
        }
        
        return ["status" =>1 ];
    }
    
    public function updateAccount($user, $data) {
        try {
            $user->email = $data['email'];
            if(($data['password'] != null) && ($data['password_confirmation'] != null)) {
                Validator::make($data, User::$user_security)->validate();
                $user->password = Hash::make($data['password']);
            }
            $user->save();
            
            return ['status' => 1];
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function updateAvatar($user, $data) {
        
        if(isset($data['user_avatar'])) {
            
            Validator::make($data, User::$user_avatar)->validate();
            $image = $data['user_avatar'];
            
            $mime_type = $image->getClientOriginalExtension();
            $image_name = substr(md5(rand()), 0, 15) . '.' . $mime_type;
            
            $image_tmp = Image::make($image);
            $image_crop = $image_tmp->resize(100, 100)->save(public_path() . '/images/profile/' . $image_name);
            
            if($user->user_extras->photo != '/images/user_placeholder.png') {
                File::delete($user->user_extras->photo);
            }
            
            $user->user_extras->photo = '/images/profile/'.$image_name;
            $user->user_extras->save();
            
            return ['status' => 1];
        }
        return ['status' => 0];
    }

    public function deleteUserAccount($id) {
        try {

            if(Auth::user()->id == $id) {
                $account = Auth::user();
                $account->delete();
            }
            return ['status' => 1, 'message' => 'ok'];
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
}