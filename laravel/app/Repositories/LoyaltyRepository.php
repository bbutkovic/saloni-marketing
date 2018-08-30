<?php

namespace App\Repositories;

use App\Models\Booking\Booking;
use Illuminate\Support\Facades\Auth;
use App\Models\{Location,Salons};
use App\Models\Salon\{
    Category, LoyaltyManagement, LoyaltyDiscounts, Service, HappyHour, Vouchers, LoyaltyPrograms
};
use DB;
use Illuminate\Support\Facades\Session;

class LoyaltyRepository {

    public function saveLoyaltySettings($salon, $data) {
        
        $loyalty_settings = LoyaltyManagement::where('salon_id', $salon->id)->first();
        
        if($loyalty_settings === null) {
            $loyalty_settings = new LoyaltyManagement;
            $loyalty_settings->salon_id = $salon->id;
        }
        
        try {
            
            $loyalty_settings->arrival_points = $data['arrival_points'];
            $loyalty_settings->social_points = $data['social_points'];
            $loyalty_settings->referral_points = $data['referral_points'];
            $loyalty_settings->money_spent = $data['money_spent'];
            $loyalty_settings->max_points = $data['max_points'];
            $loyalty_settings->expire_date = $data['expire_date'];
            $loyalty_settings->share_title = $data['share_title'] ?? null;
            $loyalty_settings->share_desc = $data['share_desc'] ?? null;
            $loyalty_settings->save();
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function addNewDiscounts($data) {
        
        try {
            
            DB::beginTransaction();
            $salon = Salons::find(Auth::user()->salon_id);

            foreach($data as $discount) {
                if($discount['discount'] != null && $discount['points'] != null) {
                    $new_discount = new LoyaltyDiscounts;
                    $new_discount->salon_id = $salon->id;
                    $new_discount->discount = $discount['discount'];
                    $new_discount->points = $discount['points'];
                    $new_discount->save();
                }
            }
            
            DB::commit();
            
            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function updateDiscounts($data) {
        
        try {
            
            DB::beginTransaction();
            
            foreach($data as $discount) {
                $update_discount = LoyaltyDiscounts::find($discount['id']);
                $update_discount->discount = $discount['discount'];
                $update_discount->points = $discount['points'];
                $update_discount->save();
            }
            
            DB::commit();
            
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }
    
    public function deleteDiscount($data) {
        
        try {
            
            $discount = LoyaltyDiscounts::find($data['discount_id']);
            $discount->delete();
            return ['status' => 1];
            
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
        
    }
    
    public function updateServicesPoints($data) {
        
        try {
        
            foreach($data['discounts'] as $key=>$discount) {
                $service = Service::find($discount['service']);
                $service->allow_discounts = $discount['allow_discount'];
                $service->award_points = $data['award_points'][$key]['award_points'];
                $service->points_awarded = $data['points'][$key]['service_points'];
                $service->save();
            }
            
            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
        
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => trans('salon.error_updating')];
        }
    }

    public function updateHappyHour($data) {
        if($location = Location::find(Auth::user()->location_id)) {
            try {
                if(!$location->happy_hour_location->isNotEmpty()) {
                    $happy_hour1 = new HappyHour;
                    $happy_hour2 = new HappyHour;
                    $happy_hour3 = new HappyHour;
                    $happy_hour4 = new HappyHour;
                    $happy_hour5 = new HappyHour;
                    $happy_hour6 = new HappyHour;
                    $happy_hour7 = new HappyHour;
                } else {
                    $happy_hour1 = HappyHour::where('location_id', $location->id)->where('day', 'Monday')->first();
                    $happy_hour2 = HappyHour::where('location_id', $location->id)->where('day', 'Tuesday')->first();
                    $happy_hour3 = HappyHour::where('location_id', $location->id)->where('day', 'Wednesday')->first();
                    $happy_hour4 = HappyHour::where('location_id', $location->id)->where('day', 'Thursday')->first();
                    $happy_hour5 = HappyHour::where('location_id', $location->id)->where('day', 'Friday')->first();
                    $happy_hour6 = HappyHour::where('location_id', $location->id)->where('day', 'Saturday')->first();
                    $happy_hour7 = HappyHour::where('location_id', $location->id)->where('day', 'Sunday')->first();
                }

                DB::beginTransaction();
                $happy_hour1->location_id = $location->id;
                $happy_hour1->day = 'Monday';
                $happy_hour1->status = isset($data['select_Monday']) ? 1 : 0;
                $happy_hour1->start = $data['starting_time_1'];
                $happy_hour1->end = $data['end_time_1'];
                $happy_hour1->save();

                $happy_hour2->location_id = $location->id;
                $happy_hour2->day = 'Tuesday';
                $happy_hour2->status = isset($data['select_Tuesday']) ? 1 : 0;
                $happy_hour2->start = $data['starting_time_2'];
                $happy_hour2->end = $data['end_time_2'];
                $happy_hour2->save();

                $happy_hour3->location_id = $location->id;
                $happy_hour3->day = 'Wednesday';
                $happy_hour3->status = isset($data['select_Wednesday']) ? 1 : 0;
                $happy_hour3->start = $data['starting_time_3'];
                $happy_hour3->end = $data['end_time_3'];
                $happy_hour3->save();

                $happy_hour4->location_id = $location->id;
                $happy_hour4->day = 'Thursday';
                $happy_hour4->status = isset($data['select_Thursday']) ? 1 : 0;
                $happy_hour4->start = $data['starting_time_4'];
                $happy_hour4->end = $data['end_time_4'];
                $happy_hour4->save();

                $happy_hour5->location_id = $location->id;
                $happy_hour5->day = 'Friday';
                $happy_hour5->status = isset($data['select_Friday']) ? 1 : 0;
                $happy_hour5->start = $data['starting_time_5'];
                $happy_hour5->end = $data['end_time_5'];
                $happy_hour5->save();

                $happy_hour6->location_id = $location->id;
                $happy_hour6->day = 'Saturday';
                $happy_hour6->status = isset($data['select_Saturday']) ? 1 : 0;
                $happy_hour6->start = $data['starting_time_6'];
                $happy_hour6->end = $data['end_time_6'];
                $happy_hour6->save();

                $happy_hour7->location_id = $location->id;
                $happy_hour7->day = 'Sunday';
                $happy_hour7->status = isset($data['select_Sunday']) ? 1 : 0;
                $happy_hour7->start = $data['starting_time_7'];
                $happy_hour7->end = $data['end_time_7'];
                $happy_hour7->save();

                $location->happy_hour_discount = $data['discount'];
                $location->save();

                DB::commit();

                return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

            } catch (Exception $exc) {
                return ['status' => 0, 'message' => $exc->getMessage()];
            }

        }

        return ['status' => 0, 'message' => trans('salon.location_not_found')];

    }

    public function createNewVoucher($data) {
        try {
            $location = Location::find(Auth::user()->location_id);
            $location_name = $this->setvalidURL($location->location_name);

            $voucher = new Vouchers;
            $voucher->location_id = $location->id;
            $voucher->name = $data['name'];
            $voucher->amount = $data['amount'];
            $voucher->expire_date = date('Y-m-d', strtotime($data['expire_date']));
            $voucher->discount = $data['discount'];
            $voucher->code = $location_name . '-' . substr(sha1(mt_rand()),17,6);
            $voucher->save();

            return ['status' => 1, 'message' => trans('salon.voucher_created')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function setvalidURL($url) {

        $a = ['À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö',
            'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ',
            'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ',
            'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ',
            'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ',
            'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ',
            'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů',
            'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ',
            'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', ' - ', '/', '(',
            ')', ' ', ':', '%'];

        $b = ['A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O',
            'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n',
            'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c',
            'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g',
            'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L',
            'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R',
            'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U',
            'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u',
            'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', '',
            '', '', '', '', '', ''];

        return strtolower(str_replace($a, $b, $url));

    }

    public function updateVoucher($data) {

        if($voucher = Vouchers::find($data['id'])) {

            $voucher->name = $data['name'];
            $voucher->amount = $data['amount'];
            $voucher->expire_date = date('Y-m-d', strtotime($data['expire_date']));
            $voucher->discount = $data['discount'];
            $voucher->code = $data['code'];
            $voucher->save();

            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
        }

        return ['status' => 0, 'message' => trans('salon.error_updating')];

    }

    public function deleteVoucher($id) {

        if($voucher = Vouchers::find($id)) {
            $voucher->delete();
            return ['status' => 1, 'message' => trans('salon.deleted_successfully')];
        }

        return ['status' => 0, 'message' => trans('salon.delete_failed')];

    }

    public function changeLoyaltyProgram($data) {

        if($location = Location::find(Auth::user()->location_id)) {

            try {
                $loyalty_program = LoyaltyPrograms::where('location_id', $location->id)->first();
                if($loyalty_program === null) {
                    $loyalty_program = new LoyaltyPrograms;
                }
                $loyalty_program->location_id = $location->id;
                $loyalty_program->loyalty_type = $data['type'];
                $loyalty_program->expire_date = $data['expire_date'] ?? null;
                switch($data['type']) {
                    case 1:
                        $loyalty_program->arrival_points = $data['arrival_points'];
                        $loyalty_program->required_arrivals = $data['required_arrivals'];
                        $loyalty_program->max_amount = $data['max_amount'];

                        break;
                    case 2:
                        $loyalty_program->arrival_points = $data['arrival_points'];
                        $loyalty_program->required_arrivals = $data['required_arrivals'];
                        $group = implode(',', $data['service_group']);
                        $loyalty_program->service_group = $group;
                        break;
                    case 3:
                        $loyalty_program->social_points = $data['social_points'] ?? null;
                        $loyalty_program->referral_points = $data['referral_points'] ?? null;
                        $loyalty_program->money_spent = $data['money_spent'] ?? null;
                        $loyalty_program->max_points = $data['max_points'] ?? null;
                        $loyalty_program->share_title = $data['share_title'] ?? null;
                        $loyalty_program->share_desc = $data['share_desc'] ?? null;
                        if(isset($data['discounts']) && count($data['discounts']) > 0) {
                            $this->addNewDiscounts($data['discounts']);
                        }
                        if(isset($data['existing_discounts']) && count($data['existing_discounts']) > 0) {
                            $this->updateDiscounts($data['existing_discounts']);
                        }
                        break;
                }
                $loyalty_program->save();

                return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

            } catch (Exception $exc) {
                return ['status' => 0, 'message' => $exc->getMessage()];
            }
        }

        return ['status' => 0, 'message' => trans('salon.location_not_found')];

    }

    public function getVouchers() {

        if($location = Location::find(Auth::user()->location_id)) {

            $vouchers = Vouchers::where('location_id', $location->id)->where('amount', '>', 0)->get();

            return ['status' => 1, 'vouchers' => $vouchers];

        }

        return ['status' => 0, 'message' => trans('salon.error_fetching_vouchers')];

    }

    public function getLoyaltyTypeInfo($data) {

        if($data['loyalty_type'] === 2) {
            $location = Location::find(Auth::user()->location_id);
            $groups_arr = [];

            $group_start = explode(',', $data['service_group']);
            foreach($group_start as $group_single) {
                $group_type = explode('-', $group_single)[0];
                $group_arr[] = explode('-', $group_single)[1];
            }

            switch ($group_type) {
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

            return ['type' => $group_type, 'groups' => $group_arr, 'group_list' => $group];
        }

    }

    public function getFreeGroupsInfo($data, $location) {

        $group_arr = [];
        $service_arr = [];
        $group_start = explode(',', $data['service_group']);
        foreach($group_start as $group_single) {
            $group_type = explode('-', $group_single)[0];
            $group_arr[] = explode('-', $group_single)[1];
        }

        switch ($group_type) {
            case 0:
                foreach($location->categories as $category) {
                    if(in_array($category->id, $group_arr)) {
                        $service_list = Service::where('category', $category->id)->get();
                        foreach($service_list as $service) {
                            $service_arr[$category->name][] = [
                                'id' => $service->id,
                                'name' => $service->service_details->name
                            ];
                        }
                    }
                }
                break;
            case 1:
                foreach($location->categories as $category) {
                    foreach($category->group as $service_group) {
                        if(in_array($service_group->id, $group_arr)) {
                            $service_list = Service::where('group', $service_group->id)->get();
                            foreach($service_list as $service) {
                                $service_arr[$service_group->name][] = [
                                    'id' => $service->id,
                                    'name' => $service->service_details->name
                                ];
                            }
                        }
                    }
                }
                break;
            case 2:
                foreach($location->categories as $category) {
                    foreach($category->group as $service_group) {
                        foreach($service_group->subcategory as $subgroup) {
                            if(in_array($subgroup->id, $group_arr)) {
                                $service_list = Service::where('sub_group', $subgroup->id)->get();
                                foreach($service_list as $service) {
                                    $service_arr[$subgroup->name][] = [
                                        'id' => $service->id,
                                        'name' => $service->service_details->name
                                    ];
                                }
                            }
                        }
                    }
                }
                break;
            case 3:
                foreach($location->services as $service) {
                    if(in_array($service->id, $group_arr)) {
                        $service_arr[$service->service_category->name][] = [
                            'id' => $service->id,
                            'name' => $service->service_details->name
                        ];
                    }
                }
                break;
        }

        return ['group' => $service_arr];
    }

    public function awardSocialPoints() {
        try {
            $booking_id = Session::get('facebook_share_booking');
            $booking = Booking::find($booking_id);
            if($booking != null) {
                $location = $booking->booking_location;
                $salon = Salons::find($location->salon_id);
                $loyalty_program = LoyaltyPrograms::where('location_id', $location->id)->first();
                if($loyalty_program != null && $loyalty_program->loyalty_type === 3) {
                    $loyalty_management = LoyaltyManagement::where('salon_id', $salon->id)->first();
                    $client = $booking->client;
                    $client->loyalty_points = $client->loyalty_points + $loyalty_management->social_points;

                    if($client->save()) {
                        $user = Auth::user();
                        $user->pin = null;
                        $user->save();
                        Session::forget('facebook_share_booking');
                        Session::forget('facebook_share');
                        Session::forget('social_points');
                        Session::forget('url');
                        Session::forget('title');
                        Session::forget('description');
                    }

                    return ['status' => 1, 'message' => trans('salon.social_points_awarded', $loyalty_management->social_points)];
                }
            }
            return ['status' => 0, 'message' => trans('salon.could_not_award_points')];
        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

}