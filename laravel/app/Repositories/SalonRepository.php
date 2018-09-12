<?php

namespace App\Repositories;

use App\Models\Salon\SalonPaymentOptions;
use Exception;
use App\User;
use App\Models\Users\UserExtras;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\{Location,LocationExtras,LocalHours,Salons,SalonService,StaffServices,BillingSalon,BillingLocation,Services};
use App\Models\Salon\{
    LoyaltyDiscounts,
    LoyaltyManagement,
    SalonExtras,
    Category,
    Group,
    SubCategory,
    Service,
    ServiceDetails,
    ServiceStaff,
    ScheduleChanges,
    CustomFields,
    SelectOptions,
    LocationPhotos
};
use App\Models\Booking\{BookingFields,CalendarColors,CustomStyles,Clients,Booking,BookingDetails};
use App\Models\Website\{WebsiteContent};
use App\Models\Clients\{ClientSettings,ClientReferrals,ClientFields};
use App\Notifications\{EmailVerification,CreatedAccountVerify};
use Illuminate\Support\Facades\{Auth,Validator,Hash,URL};
use File;
use DB;

class SalonRepository {
    
    public function getUserSalon() {
        
        $salon = Salons::where('id', Auth::user()->salon_id)->first();
        
        if($salon === null) {
            return ['status' => 0];
        }
        
        return ['status' => 1, 'salon' => $salon];
        
    }
    
    public function getSalonById($id) {
        
        $salon = Salons::where('id', $id)->first();
        
        return $salon;
        
    }
    
    public function createSalon($data) {

        try {
            $salon = Salons::max('id');
            $salon_id = $salon + 1;

            $unique_url = strtolower(str_replace(' ', '-', $data['business_name']));
            $check_url = Salons::where('unique_url', $unique_url)->first();
            if($check_url != null) {
                $extra_string = substr(md5(rand()), 0, 10);
                $unique_url = $unique_url . '-' . $extra_string;
            }

            DB::beginTransaction();

            $salon = new Salons;
            $salon->id = $salon_id;
            $salon->time_format = 'time-24';
            $salon->business_name = $data['business_name'];
            $salon->contact_name = Auth::user()->first_name . ' ' . Auth::user()->last_name;
            $salon->email_address = $data['business_email'];
            $salon->address = $data['salon_address'];
            $salon->city = $data['salon_city'];
            $salon->zip_code = $data['salon_zip'];
            $salon->country = $data['salon_country'];
            $salon->week_starting_on = 1;
            $salon->currency = 'EUR';
            $salon->unique_url = $unique_url;
            $salon->save();

            $salon_billing = new BillingSalon;
            $this->addBilling($salon_billing, $salon_id, null, null, null, null, null, null);

            $salon_extras = new SalonExtras;
            $salon_extras->salon_id = $salon_id;
            $salon_extras->email_staff_rosters = 0;
            $salon_extras->email_day = 'Monday';
            $salon_extras->email_time = '15:00';
            $salon_extras->save();

            $salon_website_content = new WebsiteContent;
            $salon_website_content->salon_id = $salon_id;
            $salon_website_content->save();

            $loyalty_discounts = new LoyaltyDiscounts();
            $loyalty_discounts->salon_id = $salon_id;
            $loyalty_discounts->discount = 5;
            $loyalty_discounts->points = 150;

            $loyalty_discounts1 = new LoyaltyDiscounts;
            $loyalty_discounts1->salon_id = $salon_id;
            $loyalty_discounts1->discount = 10;
            $loyalty_discounts1->points = 200;

            $loyalty_discounts2 = new LoyaltyDiscounts;
            $loyalty_discounts2->salon_id = $salon_id;
            $loyalty_discounts2->discount = 15;
            $loyalty_discounts2->points = 250;

            $salon_payment1 = new \App\Models\Salon\SalonPaymentOptions;
            $salon_payment1->salon_id = $salon_id;
            $salon_payment1->payment_gateway = 'paypal';
            $salon_payment1->status = 0;
            $salon_payment1->public_key = null;
            $salon_payment1->private_key = null;
            $salon_payment1->save();

            $salon_payment2 = new \App\Models\Salon\SalonPaymentOptions;
            $salon_payment2->salon_id = $salon_id;
            $salon_payment2->payment_gateway = 'stripe';
            $salon_payment2->status = 0;
            $salon_payment2->public_key = null;
            $salon_payment2->private_key = null;
            $salon_payment2->save();

            $salon_payment3 = new \App\Models\Salon\SalonPaymentOptions;
            $salon_payment3->salon_id = $salon_id;
            $salon_payment3->payment_gateway = 'wspay';
            $salon_payment3->status = 0;
            $salon_payment3->public_key = null;
            $salon_payment3->private_key = null;
            $salon_payment3->save();

            //insert location
            if($data['with_location'] == 1) {
                $location = new Location;
                $location->salon_id = $salon_id;
                $location->time_format = $salon->time_format;
                $location->location_name = $data['business_name'];
                $location->address = $data['salon_address'];
                $location->city = $data['salon_city'];
                $location->zip = $data['salon_zip'];
                $location->country = $data['salon_country'];
                $location->unique_url = $unique_url;
                $location->save();

                $location_id = $location->id;

                $location_extras = new LocationExtras;
                $location_extras->location_id = $location_id;
                $location_extras->parking = null;
                $location_extras->credit_cards = null;
                $location_extras->accessible_for_disabled = null;
                $location_extras->wifi = null;
                $location_extras->pets = null;
                $location_extras->save();

                $this->setWorkHours($location_id);

                $location_billing = new BillingLocation;
                $lid = $location->id;

                $user = Auth::user();
                $user->location_id = $location_id;
                $user->save();

                $this->addBilling($location_billing, $lid, null, null, null, null, null);

                //default category
                $category = new Category;
                $category->location_id = $location_id;
                $category->name = trans('salon.no_category');
                $category->description = null;
                $category->cat_color = '#bcbcbc';
                $category->active = 1;
                $category->save();

            }

            //insert calendar styles
            $calendar_colors = new CalendarColors;
            $calendar_colors->salon_id = $salon_id;
            $calendar_colors->save();

            $custom_styles = new CustomStyles;
            $custom_styles->salon_id = $salon_id;
            $custom_styles->save();

            //insert client settings
            $client_settings = new ClientSettings;
            $client_settings->salon_id = $salon_id;
            $client_settings->sms = 0;
            $client_settings->email = 0;
            $client_settings->viber = 0;
            $client_settings->facebook = 0;
            $client_settings->name_format = 'first_last';
            $client_settings->save();

            $client_referrals1 = new ClientReferrals;
            $client_referrals1->salon_id = $salon_id;
            $client_referrals1->name = 'Email';
            $client_referrals1->save();

            $client_referrals2 = new ClientReferrals;
            $client_referrals2->salon_id = $salon_id;
            $client_referrals2->name = 'Friend';
            $client_referrals2->save();

            $client_referrals3 = new ClientReferrals;
            $client_referrals3->salon_id = $salon_id;
            $client_referrals3->name = 'Google';
            $client_referrals3->save();

            $client_referrals4 = new ClientReferrals;
            $client_referrals4->salon_id = $salon_id;
            $client_referrals4->name = 'Newspaper';
            $client_referrals4->save();

            $client_referrals5 = new ClientReferrals;
            $client_referrals5->salon_id = $salon_id;
            $client_referrals5->name = 'Other sources';
            $client_referrals5->save();

            $client_fields = new ClientFields;
            $client_fields->salon_id = $salon_id;
            $client_fields->first_name = 1;
            $client_fields->last_name = 1;
            $client_fields->phone = 1;
            $client_fields->email = 1;
            $client_fields->address = 0;
            $client_fields->gender = 1;
            $client_fields->save();

            DB::commit();

            $user = Auth::user();
            $user->salon_id = $salon_id;
            $user->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }
    //update data for specific salon
    public function updateSalon($salon_data, $salon) {

        try {

            $image_name = $salon->logo;

            if(isset($salon_data['salon_logo']) && $salon_data['salon_logo'] != 'undefined') {
                $salon_logo = Image::make($salon_data['salon_logo']);
                if($salon_logo->width() > 200) {
                    $salon_logo->resize(200, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    if ($salon_logo->height() > 100) {
                        $salon_logo->crop(200, 100);
                    }
                }

                if($salon_logo->width() <= 200 || $salon_logo->height() <= 100) {
                    $mime_type = $salon_data['salon_logo']->getClientOriginalExtension();
                    $image_name = substr(md5(rand()), 0, 15) . '.' . $mime_type;
                    $salon_logo->save(public_path() . '/images/salon-logo/' . $image_name);

                    //delete old salon logo
                    if($salon->logo != null) {
                        unlink(public_path() . '/images/salon-logo/' . $salon->logo);
                    }

                } else {
                    return ['status' => 0, 'message' => trans('salon.logo_dim_not_allowed')];
                }
            }

            $salon->business_name = $salon_data['salon_name'];
            $salon->business_type = $salon_data['salon_type'];
            $salon->country = $salon_data['salon_country'];
            $salon->address = $salon_data['salon_address'];
            $salon->city = $salon_data['salon_city'];
            $salon->zip_code = $salon_data['salon_zip'];
            $salon->time_format = isset($salon_data['time_format']) ? $salon_data['time_format'] : 'time-24';
            $salon->time_zone = isset($salon_data['time_zone']) ? $salon_data['time_zone'] : null;
            $salon->contact_name = $salon_data['contact_name'];
            $salon->business_phone = $salon_data['salon_phone'];
            $salon->mobile_phone = $salon_data['salon_mobile'];
            $salon->email_address = $salon_data['salon_email'];
            $salon->currency = $salon_data['salon_currency'];
            $salon->week_starting_on = $salon_data['week_start'];
            $salon->logo = $image_name;

            $salon->save();

            $locations = Location::where('salon_id', $salon->id)->get();
            if(count($locations) == 1) {
                $location = $locations[0];
                $location->salon_id = $salon->id;
                $location->location_name = $salon_data['salon_name'];
                $location->business_phone = $salon_data['salon_phone'];
                $location->mobile_phone = $salon_data['salon_mobile'];
                $location->email_address = $salon_data['salon_email'];
                $location->address = $salon_data['salon_address'];
                $location->city = $salon_data['salon_city'];
                $location->zip = $salon_data['salon_zip'];
                $location->country = $salon_data['salon_country'];
                $location->save();
            }

            foreach($locations as $location) {
                $location->time_format = $salon->time_format;
                $location->save();
            }

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }
    
    public function uploadImage($file) {
        
        try {

            $location = Location::find(Auth::user()->location_id);

            if(count($location->photos) < 12) {

                $mime_type = $file->getClientOriginalExtension();
                $image_name = substr(md5(rand()), 0, 15) . '.' . $mime_type;
                $file->move(public_path() . '/images/salon-websites/gallery', $image_name);

                $location_photo = new LocationPhotos;
                $location_photo->location_id = Auth::user()->location_id;
                $location_photo->name = $image_name;
                $location_photo->save();

                return ['status' => 1];
            }

            return ['status' => 0, 'message' => trans('salon.max_photos_uploaded')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function deleteLocationPhoto($id) {

        try {
        
            if($photo = LocationPhotos::find($id)) {
                
                $filename = $photo->name;
                $file_path = public_path().'/images/salon-websites/gallery/'.$filename;
                
                if(file_exists($file_path)) {
                    unlink($file_path);
                    $photo->delete();
                }

                return ['status' => 1, 'message' => trans('salon.deleted_successfully')];
                
            }
            
            return ['status' => 0, 'message' => trans('salon.file_not_found')];
            
        } catch (Exception $exc) {
        
            return ['status' => 0, 'message' => $exc->getMessage()];
        
        }
    }
    
    public function createNewLocation($location_data, $salon_id) {
        
        try {
            
            $salon = Salons::where('id', $salon_id)->first();

            $unique_url = strtolower(str_replace(' ', '-', $location_data['location_name']));
            $check_url = Location::where('unique_url', $unique_url)->first();
            if($check_url != null) {
                $extra_string = substr(md5(rand()), 0, 10);
                $unique_url = $unique_url . '-' . $extra_string;
            }
    
            $location = new Location;
            $location->salon_id = $salon_id;
            $location->location_name = $location_data['location_name'];
            $location->business_phone = $location_data['location_phone'];
            $location->mobile_phone = $location_data['location_mobile_phone'];
            $location->email_address = $location_data['location_email'];
            $location->address = $location_data['location_address'];
            $location->city = $location_data['location_city'];
            $location->zip = $location_data['location_zip'];
            $location->country = $location_data['location_country'];
            $location->time_format = $salon->time_format;
            $location->unique_url = $unique_url;
            $location->save();
            
            $location_id = $location->id;
            
            $location_extras = new LocationExtras;
            $location_extras->location_id = $location_id;
            $location_extras->parking = isset($location_data['parking']) ? $location_data['parking'] : null;
            $location_extras->credit_cards = isset($location_data['credit_cards']) ? $location_data['credit_cards'] : null;
            $location_extras->accessible_for_disabled = isset($location_data['disabled_access']) ? $location_data['disabled_access'] : null;
            $location_extras->wifi = isset($location_data['wifi']) ? $location_data['wifi'] : null;
            $location_extras->pets = isset($location_data['pets']) ? $location_data['pets'] : null;
            $location_extras->save();
            
            $this->setWorkHours($location_id);
            
            $location_billing = new BillingLocation;
            $iban = isset($location_data['billing_oib']) ? $location_data['billing_oib'] : null;
            $oib = isset($location_data['billing_iban']) ? $location_data['billing_iban'] : null;
            $swift = isset($location_data['billing_swift']) ? $location_data['billing_swift'] : null;
            $this->addBilling($location_billing, $location_id, null, null, null, null, $oib, $iban, $swift);
            
            $category = new Category;
            $category->location_id = $location_id;
            $category->name = trans('salon.no_category');
            $category->description = null;
            $category->cat_color = '#bcbcbc';
            $category->active = 1;
            $category->save();
            
            return ['status' => 1, 'location_id' => $location->id];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    private function setWorkHours($location_id) {
        $hours = new LocalHours;
        $hours->location_id = $location_id;
        $hours->day = 1;
        $hours->dayname = 'Monday';
        $hours->status = null;
        $hours->start_time = null;
        $hours->closing_time = null;
        $hours->save();
        
        $hours = new LocalHours;
        $hours->location_id = $location_id;
        $hours->day = 2;
        $hours->dayname = 'Tuesday';
        $hours->status = null;
        $hours->start_time = null;
        $hours->closing_time = null;
        $hours->save();
        
        $hours = new LocalHours;
        $hours->location_id = $location_id;
        $hours->day = 3;
        $hours->dayname = 'Wednesday';
        $hours->status = null;
        $hours->start_time = null;
        $hours->closing_time = null;
        $hours->save();
        
        $hours = new LocalHours;
        $hours->location_id = $location_id;
        $hours->day = 4;
        $hours->dayname = 'Thursday';
        $hours->status = null;
        $hours->start_time = null;
        $hours->closing_time = null;
        $hours->save();
        
        $hours = new LocalHours;
        $hours->location_id = $location_id;
        $hours->day = 5;
        $hours->dayname = 'Friday';
        $hours->status = null;
        $hours->start_time = null;
        $hours->closing_time = null;
        $hours->save();
        
        $hours = new LocalHours;
        $hours->location_id = $location_id;
        $hours->day = 6;
        $hours->dayname = 'Saturday';
        $hours->status = null;
        $hours->start_time = null;
        $hours->closing_time = null;
        $hours->save();
        
        $hours = new LocalHours;
        $hours->location_id = $location_id;
        $hours->day = 7;
        $hours->dayname = 'Sunday';
        $hours->status = null;
        $hours->start_time = null;
        $hours->closing_time = null;
        $hours->save();
    }
    
    public function getLocationList($id) {
        
        $locations = Location::where('salon_id', $id)->first();
        
        if(!isset($locations)) {
            return ['status' => 0];
        }
        
        return ['status' => 1, 'locations' => $locations];
        
    }
    
    public function getFirstLocation($salon_id) {
        
        $location_arr = [];
        $location = Location::where('salon_id', $salon_id)->first();
        if($location) {
            $location_arr['location'][] = $location;
            $location_hours = LocalHours::where('location_id', $location->id)->get();
            
            foreach($location_hours as $day) {
                $location_arr['hours'][] = $day;
            }
            return ['status' => 1, 'location' => $location_arr];
        } else {
            return ['status' => 0];
        }
        
    }
    
    public function getLocationById($salon_id, $location_id) {
        
        $location_arr = [];
        $location = Location::where('salon_id', $salon_id)->where('id', $location_id)->first();
        $location_arr['location'][] = $location;
        
        $location_hours = LocalHours::where('location_id', $location->id)->get();

        foreach($location_hours as $day) {
            $location_arr['hours'][] = $day;
        }
        
        if(!isset($location)) {
            return ['status' => 0];
        }
        
        return ['status' => 1, 'location' => $location_arr];
        
    }
    
    public function updateLocation($location_data, $location_id) {

        try {

            $location = Location::find($location_id);

            if(isset($location_data['location_logo']) && $location_data['location_logo'] != 'undefined') {
                $location_logo = Image::make($location_data['location_logo']);
                if($location_logo->width() > 200) {
                    $location_logo->resize(200, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    if ($location_logo->height() > 100) {
                        $location_logo->crop(200, 100);
                    }
                }

                $mime_type = $location_data['location_logo']->getClientOriginalExtension();
                $image_name = substr(md5(rand()), 0, 15) . '.' . $mime_type;
                $location_logo->save(public_path() . '/images/location-logo/' . $image_name);

                //delete old salon logo
                if($location->location_extras->location_photo != null && file_exists(public_path() . '/images/location-logo/' . $location->location_extras->location_photo)) {
                    unlink(public_path() . '/images/location-logo/' . $location->location_extras->location_photo);
                }

            }

            DB::beginTransaction();

            $location->location_name = $location_data['location_name'];
            $location->business_phone = $location_data['location_phone'];
            $location->mobile_phone = $location_data['location_mobile_phone'];
            $location->email_address = $location_data['location_email'];
            $location->address = $location_data['location_address'];
            $location->city = $location_data['location_city'];
            $location->zip = $location_data['location_zip'];
            $location->country = $location_data['location_country'];
            $location->lat = $location_data['location_lat'];
            $location->lng = $location_data['location_lng'];
            $location->save();

            $location_extras = LocationExtras::where('location_id', $location->id)->first();
            if(isset($image_name)) {
                $location_extras->location_photo = $image_name;
            }
            $location_extras->parking = $location_data['parking'];
            $location_extras->credit_cards = $location_data['credit_cards'];
            $location_extras->accessible_for_disabled = $location_data['disabled_access'];
            $location_extras->wifi = $location_data['wifi'];
            $location_extras->pets = $location_data['pets'];
            $location_extras->save();

            DB::commit();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }


        
    }
    
    public function updateWorkingHours($hours_data) {
        
        try {

            $location = Location::find($hours_data['location_id']);

            $hours = LocalHours::where('location_id', $location->id)->where('day', 1)->first();
            $hours->status = isset($hours_data['open_m']) ? $hours_data['open_m'] : null;
            $hours->start_time = $hours_data['time_start_m'];
            $hours->closing_time = $hours_data['time_end_m'];
            $hours->save();
            
            $hours = LocalHours::where('location_id', $location->id)->where('day', 2)->first();
            $hours->status = isset($hours_data['open_t']) ? $hours_data['open_t'] : null;
            $hours->start_time = $hours_data['time_start_t'];
            $hours->closing_time = $hours_data['time_end_t'];
            $hours->save();
            
            $hours = LocalHours::where('location_id', $location->id)->where('day', 3)->first();
            $hours->status = isset($hours_data['open_w']) ? $hours_data['open_w'] : null;
            $hours->start_time = $hours_data['time_start_w'];
            $hours->closing_time = $hours_data['time_end_w'];
            $hours->save();
            
            $hours = LocalHours::where('location_id', $location->id)->where('day', 4)->first();
            $hours->status = isset($hours_data['open_th']) ? $hours_data['open_th'] : null;
            $hours->start_time = $hours_data['time_start_th'];
            $hours->closing_time = $hours_data['time_end_th'];
            $hours->save();
            
            $hours = LocalHours::where('location_id', $location->id)->where('day', 5)->first();
            $hours->status = isset($hours_data['open_f']) ? $hours_data['open_f'] : null;
            $hours->start_time = $hours_data['time_start_f'];
            $hours->closing_time = $hours_data['time_end_f'];
            $hours->save();
            
            $hours = LocalHours::where('location_id', $location->id)->where('day', 6)->first();
            $hours->status = isset($hours_data['open_sat']) ? $hours_data['open_sat'] : null;
            $hours->start_time = $hours_data['time_start_sat'];
            $hours->closing_time = $hours_data['time_end_sat'];
            $hours->save();
            
            $hours = LocalHours::where('location_id', $location->id)->where('day', 7)->first();
            $hours->status = isset($hours_data['open_sun']) ? $hours_data['open_sun'] : null;
            $hours->start_time = $hours_data['time_start_sun'];
            $hours->closing_time = $hours_data['time_end_sun'];
            $hours->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function addBilling($model, $id, $address = null, $city = null, $zip = null, $country = null, $oib = null, $iban = null, $swift = null, $tax = null) {
        try {
            $model->id = $id;
            $model->address = $address;
            $model->city = $city;
            $model->zip = $zip;
            $model->country = $country;
            $model->oib = $oib;
            $model->iban = $iban;
            $model->swift = $swift;
            if($tax != null) {
                $model->vat = $tax;
            }
            $model->spent_sms = 0;
            $model->save(); 
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function saveLocationBilling($data) {
        try {
            $billing_info = BillingLocation::where('id', $data['input_id'])->first();
            $billing_info->address = $data['billing_address'];
            $billing_info->city = $data['billing_city'];
            $billing_info->zip = $data['billing_zip'];
            $billing_info->country = $data['billing_country'];
            $billing_info->oib = $data['billing_oib'];
            $billing_info->iban = $data['billing_iban'];
            $billing_info->swift = $data['billing_swift'];
            $billing_info->location_label = $data['location_label'] ?? null;
            $billing_info->pdv_sustav = $data['pdv_sustav'] ?? null;
            $billing_info->slijednost = $data['slijednost'] ?? null;
            $billing_info->spent_sms = 0;
            $billing_info->save();
            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function saveSalonBilling($data) {

        $salon = BillingSalon::where('id', $data['input_id'])->first();
        $address = $data['billing_address'];
        $city = $data['billing_city'];
        $zip = $data['billing_zip'];
        $country = $data['billing_country'];
        $oib = $data['billing_oib'];
        $iban = $data['billing_iban'];
        $swift = $data['billing_swift'];
        $tax = $data['tax'];
        
        $billing = $this->addBilling($salon, $data['input_id'], $address, $city, $zip, $country, $oib, $iban, $swift, $tax);
        
        if($billing['status'] === 0) {
            return ['status' => 0, 'message' => $billing['message']];
        }
        
        return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
    }
    
    public function deleteLocationData($location) {
        
        try {
            
            //DB::beginTransaction();
            
            //delete services
            //delete groups, subgroups and services under this category
            $location_categories = Category::where('location_id', $location->id)->get();
            if($location_categories->isNotEmpty()) {
                foreach($location_categories as $category) {
                    foreach($category->group as $group) {
                        if($group != null) {
                            foreach($group->service as $group_service) {
                                if($group_service != null) {
                                    $service_staff = ServiceStaff::find($group_service->service-id);
                                    $service_details = ServiceDetails::where('service_id', $group_service->id)->first();
                                    if($service_details != null) {
                                        $service_details->delete();
                                    }
                                    
                                    $service_staff->delete();
                                    $group_service->delete();
                                }
                            }
                            
                            foreach($group->subcategory as $subgroup) {
                                if($subgroup != null) {
                                    $subgroup->delete();
                                }
                            }
                            
                            $group->delete();
                        }
                    }
                    
                    $category->delete();
                }
            }
            
            //delete bookings
            $booking_list = Booking::where('location_id', $location->id)->get();
            if($booking_list->isNotEmpty()) {
                foreach($booking_list as $booking) {
                    $booking_details = BookingDetails::where('booking_id', $booking->id)->first();
                    if($booking_details != null) {
                        $booking_details->delete();
                    }
                    $booking->delete();
                }
            }
            
            //delete clients
            $clients = Clients::where('location_id', $location->id)->get();
            if($clients->isNotEmpty()) {
                foreach($clients as $client) {
                    $client->delete();
                }
            }
            
            //delete schedule changes
            $schedule_changes = ScheduleChanges::where('location_id', $location->id)->get();
            if($schedule_changes->isNotEmpty()) {
                foreach($schedule_changes as $schedule_change) {
                    $schedule_change->delete();
                }
            }
            
            //delete working hours
            $working_hours = LocalHours::where('location_id', $location->id)->get();
            if($working_hours->isNotEmpty()) {
                foreach($working_hours as $hour) {
                    $hour->delete();
                }
            }
            
            //delete extras
            $location_extras = LocationExtras::where('location_id', $location->id)->first();
            if($location_extras != null) {
                $location_extras->delete();
            }
            
            //delete billing
            $location_billing = BillingLocation::where('id', $location->id)->first();
            $location_billing->delete();
            
            //delete location
            $location->delete();
            
            return ['status' => 1];
            
            //DB::commit();
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function getHours($location) {

        $min = LocalHours::select('start_time')->where('location_id', $location)->where('status', 'on')->min('start_time');
        $max = LocalHours::select('closing_time')->where('location_id', $location)->where('status', 'on')->max('closing_time');
        $min_time = $min . ':00';
        $max_time = $max . ':00';
        
        return ['min' => $min_time, 'max' => $max_time];
        
    }
    
    public function getHiddenDays($location) {
        
        $days = LocalHours::where('location_id', $location)->where('status', null)->get();

        $hidden_days = [];
        
        foreach($days as $day) {
            switch ($day->dayname) {
                case 'Monday':
                    $day_key = 1;
                    break;
                case 'Tuesday':
                    $day_key = 2;
                    break;
                case 'Wednesday':
                    $day_key = 3;
                    break;
                case 'Thursday':
                    $day_key = 4;
                    break;
                case 'Friday':
                    $day_key = 5;
                    break;
                case 'Saturday':
                    $day_key = 6;
                    break;
                case 'Sunday':
                    $day_key = 0;
                    break;
            }
            $hidden_days[] = $day_key;
        }
        
        return $hidden_days;
    }
    
    public function saveCategory($location, $data) {
        
        if(isset($data['active_category']) && $data['active_category'] === 'on') {
            $active = 1;
        } else {
            $active = 0;
        }
        
        if(isset($data['category_id'])) {
            $category = Category::find($data['category_id']);
        } else {
            $category = new Category;
        }
        
        $category->location_id = $location->id;
        $category->name = $data['category_name'];
        $category->description = isset($data['category_desc']) ? $data['category_desc'] : null;
        $category->cat_color = $data['category_color'];
        $category->active = $active;
        $category->save();
        
        return ['status' => 1];
    }
    
    public function deleteCategory($category) {
        
        try {
            
            DB::beginTransaction();
            $category->delete();
            DB::commit();
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0];
        }
        
    }
    
    public function saveSubCategory($data) {
        
        if($data['active_sub_category'] === 'on') {
            $active = 1;
        } else {
            $active = 0;
        }
        
        if(isset($data['sub_category_id'])) {
            $sub_category = SubCategory::find($data['sub_category_id']);
        } else {
            $sub_category = new SubCategory;
            $sub_category->group_id = $data['group_id'];
        }
        
        $sub_category->name = $data['sub_category_name'];
        $sub_category->description = isset($data['sub_category_desc']) ? $data['sub_category_desc'] : null;
        $sub_category->subgroup_color = $data['subgroup_color'];
        $sub_category->active = $active;
        $sub_category->save();
        
        return ['status' => 1];
    }
    
    public function deleteSubCategory($category) {
        try {
            
            //delete services under this category
            foreach($category->service as $service) {
                if($service != null) {
                    $service_staff = ServiceStaff::find($service->service-id);
                    $service_details = ServiceDetails::where('service_id', $service->id)->first();
                    if($service_details != null) {
                        $service_details->delete();
                    }
                    
                    $service_staff->delete();
                    $service->delete();
                }
            }
            
            $category->delete();
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0];
        }
        
    }
    
    public function saveGroup($data) {
        
        if($data['active_group'] === 'on') {
            $active = 1;
        } else {
            $active = 0;
        }
        
        if(isset($data['group_id'])) {
            $group = Group::find($data['group_id']);
        } else {
            $group = new Group;
            $group->category_id = $data['category_id'];
        }
        
        $group->name = $data['group_name'];
        $group->description = isset($data['group_desc']) ? $data['group_desc'] : null;
        $group->group_color = $data['group_color'];
        $group->active = $active;
        $group->save();
        
        return ['status' => 1];
    }
    
    public function deleteGroup($group) {
        try {
            
            //delete group, subgroups and services under this category
            
            foreach($group->service as $service) {
                if($service != null) {
                    $service_staff = ServiceStaff::find($service->service-id);
                    $service_details = ServiceDetails::where('service_id', $service->id)->first();
                    if($service_details != null) {
                        $service_details->delete();
                    }
                    
                    $service_staff->delete();
                    $service->delete();
                }
            }
            
            foreach($group->subcategory as $subgroup) {
                if($subgroup != null) {
                    $subgroup->delete();
                }
            }

            $group->delete();
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function getServices($location) {
        
        $categories = Category::where('location_id', $location)->get();
        
        $services = Service::where('location_id', $location)->get();
        $service_list = [];
        
        foreach($service_list as $service) {
            $service_list[$service->id][] = [
                'service' => [
                    'category' => $service->category,
                    'group' => $service->group,
                    'subgroup' => $service->sub_group,
                    'order' => $service->order,
                ],
                'service_details' => [
                    'name' => $service->service_details->name,
                    'description' => $service->service_details->description,
                    'code' => $service->service_details->code,
                    'barcode' => $service->service_details->barcode,
                    'service_length' => $service->service_details->service_length,
                    'price_no_vat' => $service->service_details->price_no_vat,
                    'vat' => $service->service_details->vat,
                    'price' => $service->service_details->base_price * $service->service_details->tax,
                    'available' => $service->service_details->available,
                ]
            ];
        }
        
        return ['categories' => $categories, 'services' => $service_list];
        
    }
    
    public function saveService($data) {
        
        $location = Auth::user()->location_id;
        
        DB::beginTransaction();
        //if edit or creating new service
        if(isset($data['service_id'])) {
            $service = Service::find($data['service_id']);
            
            $order = $service->order;
            
        } else {
            $service = new Service;
            
            if(!isset($data['category_id']) || !isset($data['group_id'])) {
                return ['status' => 0, 'message' => trans('salon.category_group_required')];
            }
            
            //get highest order service
            if(isset($data['subgroup_id'])) {
                $order = Service::where('location_id', $location)->where('sub_group', $data['subgroup_id'])->get()->max('order');
            } else {
                $order = Service::where('location_id', $location)->where('group', $data['group_id'])->get()->max('order');
            }
            
            if($order === null) {
                $order = 0;
            }
            
            $order += 1;
        }
        
        if(!isset($data['category_id'])) {
            $category = $service->category;
            $group = $service->group;
            $sub_group = $service->sub_group;
        } else {
            $category = $data['category_id'];
            $group = $data['group_id'];
            $sub_group = isset($data['subgroup_id']) ? $data['subgroup_id'] : null;
        }
        
        if(isset($data['allow_discounts'])) {
            $allow_discounts = 1;
        } else {
            $allow_discounts = 0;
        }
        
        if(isset($data['award_points'])) {
            $award_points = 1;
            $points_awarded = $data['points_awarded'];
        } else {
            $award_points = 0;
            $points_awarded = 0;
        }
        
        try {
            $service->location_id = $location;
            $service->category = $category;
            $service->group = $group;
            $service->sub_group = $sub_group;
            $service->order = $order;
            $service->allow_discounts = $allow_discounts;
            $service->award_points = $award_points;
            $service->points_awarded = $points_awarded;
            $service->save();
            
            if(isset($data['service_id'])) {
                $service_details = $service->service_details;
            } else {
                $service_details = new ServiceDetails;
            }
            
            if(isset($data['service_available']) && $data['service_available'] === 'on') {
                $available = 1;
            } else {
                $available = 0;
            }
            
            $service_details->service_id = $service->id;
            $service_details->name = $data['service_name'];
            $service_details->description = $data['service_desc'];
            $service_details->code = $data['service_code'];
            $service_details->barcode = $data['service_barcode'];
            $service_details->service_length = $data['service_length'];
            $service_details->price_no_vat = $data['service_cost'] / (float)('1.'.$data['vat']);
            $service_details->vat = $data['vat'];
            $service_details->base_price = $data['service_cost'];
            $service_details->available = $available;
            $service_details->save();
        
            DB::commit();
            
            return ['status' => 1];
            
        } catch(Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function deleteService($service) {
        
        try {
            
            DB::beginTransaction();
            
            $service->delete();
            
            DB::commit();
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function saveServiceStaff($data) {
        
        $services = ServiceStaff::where('location_id', Auth::user()->location_id)->where('service_id', $data['service_id'])->get();
        
        try {
            if(isset($data['selected_staff'])) {
                foreach($data['selected_staff'] as $selected_staff) {
                    
                    $service_list = [];
                    
                    foreach($services as $service) {
                        
                        if(!in_array($service->user_id, $data['selected_staff'])) {
                            $service->delete();
                        }
                        
                    }
                    
                    $service_check = ServiceStaff::where('location_id', Auth::user()->location_id)->where('service_id', $data['service_id'])->where('user_id', $selected_staff)->first();
                    
                    if($service_check === null) {
                        $new_service = new ServiceStaff;
                        $new_service->location_id = Auth::user()->location_id;
                        $new_service->service_id = $data['service_id'];
                        $new_service->user_id = $selected_staff;
                        $new_service->save();
                    }
                    
                }
            } else {
                
                foreach($services as $service) {
                    $service->delete();
                }
                
            }
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            
            return ['status' => 0, 'message' => $exc->getMessage()];
            
        }
        
    }
    
    public function changeOrder($services) {
        
        $categories = Category::where('location_id', Auth::user()->location_id)->get();

        try {
            
            foreach($categories as $category) {
                foreach($category->group as $group) {
                    
                    $order = 0;
                    
                    foreach($services['order'] as $service_order) {
                        
                        if($group->id == $service_order['group'] && $service_order['subgroup'] == null) {
                            
                            $order++;
                            
                            $service = Service::find($service_order['id']);
                            $service->order = $order;
                            $service->save();
                        
                        }
                    }
                        
                    foreach($group->subcategory as $subgroup) {
                        
                        $order = 0;
                        
                        foreach($services['order'] as $service_order) {
                        
                            if($group->id == $service_order['group'] && $service_order['subgroup'] == $subgroup->id) {
                                    
                                $order++;
                                
                                $service = Service::find($service_order['id']);
                                $service->order = $order;
                                $service->save();
                            }
                            
                        }
                        
                    }
                }
            }
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            
            return ['status' => 0, 'message' => $exc->getMessage()];
            
        }
                    
    }
    
    public function getUniqueCodes($location) {
        
        $code = substr(md5(rand()), 0, 8);
        
        $min = 1000000000;
        $max = 9999999999;
        $barcode = mt_rand($min, $max);
        
        $services = Service::where('location_id', $location->id)->get();
        
        foreach($services as $service) {
            if($service->service_details->code === $code || $service->service_details->barcode === $barcode) {
                $this->getUniqueCodes($location);
            }
        }
        
        return ['status' => 1, 'code' => $code, 'barcode' => $barcode];

    }
    
    public function saveFields($data) {
        
        try {
            
            $location = Location::find(Auth::user()->location_id);

            if($data['field_location'] === null) {
                $field_location = 'booking';
            } else {
                $field_location = $data['field_location'];
            }
            $custom_status = CustomFields::where('location_id', $location->id)->where('field_location', $field_location)->get();
            
            $field_count = count($custom_status);
            $field_count += 1;

            if ($field_location === 'booking' && $field_count > 3) {
                return ['status' => 0, 'message' => trans('salon.max_fields_reached')];
            }
            
            $existing_fields = CustomFields::where('location_id', $location->id)->where('field_location', $data['field_location'])->get();

            if($existing_fields->isNotEmpty()) {
                $number_of_fields = count($existing_fields);
                $counter = $number_of_fields + 1;
            } else {
                $counter = 1;
            }

            $select_field = new CustomFields;
            $select_field->location_id = $location->id;
            $select_field->field_location = $data['field_location'];
            $select_field->field_name = 'custom_field_'.$counter;
            $select_field->field_title = $data['main_field_name'];
            $select_field->field_type = $data['field_input_type'];
            $select_field->field_status = 1;
            $select_field->save();
            
            if($data['field_input_type'] === '2') {
                //store select options
                foreach($data['select_options'] as $select_input) {
                    $select_options = new SelectOptions;
                    $select_options->field_id = $select_field->id;
                    $select_options->option_name = $select_input;
                    $select_options->option_value = $select_input;
                    $select_options->save();
                }
            }
            
            return ['status' => 1, 'message' => trans('salon.updated_successfuly'), 'field' => $select_field];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function deleteField($data) {
        
        try {
            
            if($custom_field = CustomFields::find($data['id'])) {
                
                $custom_field->delete();
                
                return ['status' => 1];
                
            }
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function updateCustomFields($data) {
        
        try {
        
            if($custom_field = CustomFields::find($data['field_id'])) {
                
                $select_options = SelectOptions::where('field_id', $custom_field->id)->get();
                
                if($data['field_type'] != null) {
                    $field_type = $data['field_type'];
                } else {
                    $field_type = $custom_field->field_type;
                }
                //update field info
                $custom_field->field_type = $field_type;
                $custom_field->field_title = $data['field_title'];
                $custom_field->save();
                
                //add new select field options
                if(isset($data['new_options'])) {
                    foreach($data['new_options'] as $new_field) {
                        $new_options = new SelectOptions;
                        $new_options->field_id = $custom_field->id;
                        $new_options->option_name = $new_field['value'];
                        $new_options->option_value = $new_field['value'];
                        $new_options->save();
                    }
                }
                
                //update select field options
                if($select_options->isNotEmpty()) {
                    foreach($select_options as $option) {
                        if($field_type === 1) {
                            $option->delete();
                        } else {
                            $check = 0;
                            foreach($data['select_options'] as $key=>$select_opt) {
                                if($select_opt['name'] == $option->id) {
                                    $check = 1;
                                    $key_check = $key;
                                    break;
                                }
                            }
                            if($check === 1) {
                                $option->option_name = $data['select_options'][$key_check]['value'];
                                $option->option_value = $data['select_options'][$key_check]['value'];
                                $option->save();
                            } else {
                                $option->delete();
                            }
                            
                        }
                    }
                }
    
            }
            
            return ['status' => 1, 'message' => trans('salon.updated_successfuly'), 'field' => $custom_field];
            
        } catch (Exception $exc) {
            
            return ['status' => 0, 'message' => $exc->getMessage()];
            
        }
        
    }
    
    public function getActiveCategories($location) {
        
        $category_arr_tmp = [];
        $category_arr = [];
        
        foreach($location->services as $service) {
            $category_arr_tmp[] = $service->category;
        }
        
        $categories = array_unique($category_arr_tmp);
        
        foreach($categories as $category) {
            $cat = Category::find($category);
            $category_arr[] = $cat;
        }
        
        return $category_arr;
        
    }
    
    public function getServicesByCategory($location) {

        try {
            $service_arr = [];
            $salon = Salons::find($location->salon_id);

            foreach($location->services as $service) {
                if($service->service_details->description != null) {
                    $service_details = $service->service_details->description;
                } else {
                    $service_details = ' ';
                }

                $service_arr[$service->category][] = [
                    'id' => $service->id,
                    'name' => $service->service_details->name,
                    'category_name' => $service->service_category->name,
                    'subgroup_name' => isset($service->service_subgroup->name) ? $service->service_subgroup->name : '',
                    'desc' => $service_details,
                    'price' => $service->service_details->base_price . ' ' . $salon->currency
                ];
            }


            return ['status' => 1, 'services' => $service_arr];
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }

    public function getColors($location) {

        $categories = $location->categories;
        $cat_colors = [];
        $group_colors = [];
        $subgroup_colors = [];

        if($categories->isNotEmpty()) {
            foreach($categories as $category) {
                $cat_colors[] = $category->cat_color;
                foreach($category->group as $group) {
                    $group_colors[] = $group->group_color;
                    foreach($group->subcategory as $subgroup) {
                        $subgroup_colors[] = $subgroup->subgroup_color;
                    }
                }
            }
        }

        return ['cat_colors' => $cat_colors, 'group_colors' => $group_colors, 'subgroup_colors' => $subgroup_colors];

    }

    public function getServiceGroups($id) {

        try {

            $location = Location::find(Auth::user()->location_id);
            $group = [];
            switch ($id) {
                case 0:
                    foreach($location->categories as $category) {
                        $group[] = [
                            'id' => $category->id,
                            'name' => $category->name
                        ];
                    }
                    break;
                case 1:
                    foreach($location->categories as $category) {
                        foreach($category->group as $service_group) {
                            $group[] = [
                                'id' => $service_group->id,
                                'name' => $service_group->name . ' (' . $service_group->get_category->name . ')'
                            ];
                        }
                    }
                    break;
                case 2:
                    foreach($location->categories as $category) {
                        foreach($category->group as $service_group) {
                            foreach($service_group->subcategory as $subgroup) {
                                $group[] = [
                                    'id' => $subgroup->id,
                                    'name' => $subgroup->name . ' (' . $service_group->name . ')'
                                ];
                            }
                        }
                    }
                    break;
                case 3:
                    foreach($location->services as $service) {
                        $group[] = [
                            'id' => $service->id,
                            'name' => $service->service_details->name . ' (' . $service->service_category->name . ')'
                        ];
                    }
                    break;
            }

            return ['status' => 1, 'group' => $group];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function deleteSalon($id) {
        try {

            if(Auth::user()->hasRole('superadmin')) {
                $salon = Salons::find($id);
                $salon->delete();
            }

            return ['status' => 1, 'message' => trans('salon.salon_deleted')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

    public function importServices($services) {

        try {

            $current_location = Location::find(Auth::user()->location_id);

            foreach($services['services'] as $service_id) {
                $check = false;
                $service = Service::find($service_id);
                $service_cat = $service->service_category;
                $service_group = $service->service_group;
                $service_subgroup = $service->service_subgroup;

                //check if category, group and subgroup with the same name exist in current location
                $current_location_cat = Category::where('location_id', $current_location->id)->where('name', $service_cat->name)->first();
                $current_location_subgroup = null;
                if($current_location_cat != null) {
                    $cat_id = $current_location_cat->id;
                    $current_location_group = Group::where('category_id', $current_location_cat->id)->where('name', $service_group->name)->first();
                } else {
                    $cat_id = null;
                    $current_location_group = null;
                }
                if($current_location_group != null) {
                    $group_id = $current_location_group->id;
                    if($service_subgroup != null) {
                        $current_location_subgroup = SubCategory::where('group_id', $current_location_group->id)->where('name', $service_subgroup->name)->first();
                    }
                } else {
                    $group_id = null;
                }
                if($current_location_subgroup != null) {
                    $subgroup_id = $current_location_subgroup->id;
                } else {
                    $subgroup_id = null;
                }

                $check_services = Service::where('location_id', $current_location->id)
                    ->where('category', $cat_id)
                    ->where('group', $group_id)
                    ->where('sub_group', $subgroup_id)
                    ->get();

                if($check_services->isNotEmpty()) {
                    foreach($check_services as $check_service) {
                        if($check_service->service_details->name == $service->service_details->name) {
                            $check = true;
                        }
                    }
                }

                if(!$check) {
                    if($current_location_cat === null) {
                        $current_location_cat = new Category;
                        $current_location_cat->location_id = $current_location->id;
                        $current_location_cat->name = $service_cat->name;
                        $current_location_cat->description = $service_cat->description;
                        $current_location_cat->cat_color = $service_cat->cat_color;
                        $current_location_cat->active = $service_cat->active;
                        $current_location_cat->save();

                        $cat_id = $current_location_cat->id;
                    }

                    if($current_location_group === null) {
                        $current_location_group = new Group;
                        $current_location_group->category_id = $cat_id;
                        $current_location_group->name = $service_group->name;
                        $current_location_group->description = $service_group->description;
                        $current_location_group->group_color = $service_group->group_color;
                        $current_location_group->active = $service_group->active;
                        $current_location_group->save();

                        $group_id = $current_location_group->id;
                    }

                    if($current_location_subgroup === null && $service_subgroup != null) {
                        $current_location_subgroup = new SubCategory;
                        $current_location_subgroup->group_id = $group_id;
                        $current_location_subgroup->name = $service_subgroup->name;
                        $current_location_subgroup->description = $service_subgroup->description;
                        $current_location_subgroup->subgroup_color = $service_subgroup->subgroup_color;
                        $current_location_subgroup->active = $service_subgroup->active;
                        $current_location_subgroup->save();

                        $subgroup_id = $current_location_subgroup->id;
                    }

                    $new_service = new Service;
                    $new_service->location_id = $current_location->id;
                    $new_service->category = $cat_id;
                    $new_service->group = $group_id;
                    $new_service->sub_group = $subgroup_id;
                    $new_service->order = $service->order;
                    $new_service->award_points = $service->award_points;
                    $new_service->allow_discounts = $service->allow_discounts;
                    $new_service->points_awarded = $service->points_awarded;
                    $new_service->points_needed = $service->points_needed;
                    $new_service->discount = $service->discount;
                    $new_service->save();

                    $new_service_details = new ServiceDetails;
                    $new_service_details->service_id = $new_service->id;
                    $new_service_details->name = $service->service_details->name;
                    $new_service_details->description = $service->service_details->description;
                    $new_service_details->code = $service->service_details->code;
                    $new_service_details->barcode = $service->service_details->barcode;
                    $new_service_details->service_length = $service->service_details->service_length;
                    $new_service_details->price_no_vat = $service->service_details->price_no_vat;
                    $new_service_details->vat = $service->service_details->vat;
                    $new_service_details->base_price = $service->service_details->base_price;
                    $new_service_details->available = $service->service_details->available;
                    $new_service_details->save();

                }

            }

            return ['status' => 1];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
}