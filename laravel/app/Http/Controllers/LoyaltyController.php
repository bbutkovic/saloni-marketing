<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repositories\{LoyaltyRepository,InfoRepository};
use App\Models\{Salons,Location};
use App\Models\Salon\{
    LoyaltyManagement, LoyaltyDiscounts, LoyaltyPrograms
};
use Illuminate\Support\Facades\Validator;

class LoyaltyController extends Controller
{
    protected $loyalty_repo;
    
    public function __construct() {
        $this->info_repo = new InfoRepository;
        $this->loyalty_repo = new LoyaltyRepository;
    }
    
    public function getLoyaltyManagement() {
        
        $salon = Salons::find(Auth::user()->salon_id);
        
        $location = Location::find(Auth::user()->location_id);
        
        $loyalty_settings = LoyaltyPrograms::where('location_id', $location->id)->first();

        $loyalty_type = $this->loyalty_repo->getLoyaltyTypeInfo($loyalty_settings);

        if($loyalty_type != null) {
            $groups_type = $loyalty_type['type'];
            $groups_arr = $loyalty_type['groups'];
            $group_list = $loyalty_type['group_list'];
        } else {
            $groups_type = null;
            $groups_arr = null;
            $group_list = null;
        }

        $loyalty_discounts = LoyaltyDiscounts::where('salon_id', $salon->id)->get();

        $week = $this->info_repo->getWeekDays();

        $time_list = $this->info_repo->getHoursList($salon->id);
        
        return view('salon.loyalty.loyaltyManagement', ['salon' => $salon, 'location' => $location, 'loyalty_settings' => $loyalty_settings, 'group_list' => $group_list,
                                                'loyalty_discounts' => $loyalty_discounts, 'week' => $week, 'time_list' => $time_list, 'groups_type' => $groups_type, 'groups_arr' => $groups_arr]);
        
    }

    public function getHappyHour() {

        $salon = Salons::find(Auth::user()->salon_id);

        $location = Location::find(Auth::user()->location_id);

        $week = $this->info_repo->getWeekDays();

        $time_list = $this->info_repo->getHoursList($salon->id);

        return view('salon.loyalty.happyHour', ['salon' => $salon, 'location' => $location, 'week' => $week, 'time_list' => $time_list]);
    }

    public function getGiftVouchers() {

        $salon = Salons::find(Auth::user()->salon_id);

        $location = Location::find(Auth::user()->location_id);

        return view('salon.loyalty.giftVouchers', ['salon' => $salon, 'location' => $location]);

    }
    
    public function saveLoyaltySettings(Request $request) {
        
        $salon = Salons::find(Auth::user()->salon_id);
        
        $settings = $this->loyalty_repo->saveLoyaltySettings($salon, $request->all());
        
        if($settings['status'] === 1) {
            return redirect()->back()->with('success_message', trans('salon.updated_successfuly'));
        }
        
        return redirect()->back()->with('error_message', trans('salon.error_updating'));
    }

    public function addNewDiscounts(Request $request) {
        
        $salon = Salons::find(Auth::user()->salon_id);
        
        $discounts = $this->loyalty_repo->addNewDiscounts($salon, $request->all());

        return ['status' => $discounts['status'], 'message' => $discounts['message']];
        
    }
    
    public function saveLoyaltyDiscounts(Request $request) {
        
        $salon = Salons::find(Auth::user()->salon_id);
        
        $discounts_update = $this->loyalty_repo->updateDiscounts($salon, $request->all());

        if($discounts_update['status'] === 1) {
            return redirect()->back()->with('success_message', trans('salon.updated_succesfuly'));
        }
        
        return redirect()->back()->with('error_message', trans('salon.error_updating'));
        
    }
    
    public function deleteDiscount(Request $request) {
        
        $discount_delete = $this->loyalty_repo->deleteDiscount($request->all());
        
        if($discount_delete['status'] === 1) {
            return ['status' => 1];
        }
        
        return ['status' => 0, 'message' => $discount_delete['message']];
        
    }
    
    public function updateServicesPoints(Request $request) {
        $update_services = $this->loyalty_repo->updateServicesPoints($request->all());
        return ['status' => $update_services['status'], 'message' => $update_services['message']];
    }

    public function changeHappyHourStatus(Request $request) {

        if($location = Location::find(Auth::user()->location_id)) {
            $location->happy_hour = $request->status;
            $location->save();
            return ['status' => 1, 'message' => trans('salon.updated_successfuly')];
        }

        return ['status' => 0, 'message' => trans('salon.error_updating')];

    }

    public function updateHappyHourSettings(Request $request) {

        $happy_hour_update = $this->loyalty_repo->updateHappyHour($request->all());

        if($happy_hour_update['status'] === 1) {
            return redirect()->back()->with('success_message', trans('salon.updated_successfuly'));
        }

        return redirect()->back()->with('error_message', trans('salon.error_updating'));

    }

    public function createNewVoucher(Request $request) {

        $voucher = $this->loyalty_repo->createNewVoucher($request->all());

        if($voucher['status'] === 1) {
            return ['status' => 1, 'message' => $voucher['message']];
        }

        return ['status' => 0, 'message' => $voucher['message']];

    }

    public function updateVoucher(Request $request) {

        $voucher = $this->loyalty_repo->updateVoucher($request->all());

        return ['status' => $voucher['status'], 'message' => $voucher['message']];
    }

    public function deleteVoucher($id) {

        $voucher_delete = $this->loyalty_repo->deleteVoucher($id);

        return ['status' => $voucher_delete['status'], 'message' => $voucher_delete['message']];

    }

    public function changeLoyaltyProgram(Request $request) {
        $loyalty_program = $this->loyalty_repo->changeLoyaltyProgram($request->all());
        return ['status' => $loyalty_program['status'], 'message' => $loyalty_program['message']];
    }

    public function getVouchers() {

        $vouchers = $this->loyalty_repo->getVouchers();

        if($vouchers['status'] === 1) {
            return ['status' => 1, 'vouchers' => $vouchers['vouchers']];
        }

        return ['status' => 0, 'message' => $vouchers['message']];

    }

    public function awardSocialPoints(Request $request) {

        if (gettype($request) === 'object') {
            $points = $this->loyalty_repo->awardSocialPoints();
            return ['status' => $points['status'], 'message' => $points['message']];
        }

        return ['status' => 0, 'message' => 'Unknown error'];

    }
}
