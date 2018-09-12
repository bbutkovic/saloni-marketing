<?php

namespace App\Http\Controllers;

use App\Models\BillingLocation;
use App\Models\Salon\Currencies;
use App\Models\Salon\SalonPaymentOptions;
use Illuminate\Http\Request;
use App\Models\{Salons,BillingSalon,Location,Countries};
use App\Models\Salon\{Category,Group,SubCategory,Service,ServiceStaff,CustomFields};
use App\User;
use App\Repositories\{SalonRepository,StaffRepository,InfoRepository};
use Illuminate\Support\Facades\{Validator,Auth};
use Session;
use DB;

class SalonController extends Controller
{
    protected $salon_repo;
    protected $info_repo;
    protected $staff_repo;

    public function __construct() {
        $this->salon_repo = new SalonRepository;
        $this->info_repo = new InfoRepository;
        $this->staff_repo = new StaffRepository;
    }

    public function createSalon(Request $request) {

        $salon_id = $this->salon_repo->getUserSalon();

        if($salon_id['status'] === 0) {

            $salon = $this->salon_repo->createSalon($request->all());

            if($salon['status'] != 1) {
                return redirect()->back()->with('error_message', $salon['message']);
            }

            //posalji email notifikaciju
            return redirect()->route('salonInfo', Auth::user()->salon_id)->with('success_message', $salon['message']);
        }

        return view('core.dashboard')->with('error_message', trans('salon.already_created'));

    }

    public function getSalonInfo() {

        $sid = Auth::user()->salon_id;

        $salon = $this->salon_repo->getSalonById($sid);

        $salon_locations = $this->salon_repo->getLocationList($sid);

        $currency_list = Currencies::where('active', 1)->get();

        $country_list = Countries::all();

        $business_type = $this->info_repo->getBusinessTypes();

        //payment options
        $paypal = SalonPaymentOptions::where('salon_id', $salon->id)->where('payment_gateway', 'paypal')->first();
        $stripe = SalonPaymentOptions::where('salon_id', $salon->id)->where('payment_gateway', 'stripe')->first();
        $wspay = SalonPaymentOptions::where('salon_id', $salon->id)->where('payment_gateway', 'wspay')->first();

        if(!$salon) {
            return redirect()->route('dashboard')->with('error_message', trans('salon.no_salon'));
            //return view('core.salonInfo', ['salon' => $salon]);
        }

        $time_zones = $this->info_repo->getTimeZones();

        return view('salon.salonInfo', ['salon' => $salon, 'location' => $salon_locations, 'paypal' => $paypal, 'stripe' => $stripe, 'wspay' => $wspay, 'currency_list' => $currency_list, 'time_zones' => $time_zones, 'countries' => $country_list, 'business_type' => $business_type]);

    }

    public function updateSalonInfo(Request $request) {

        if($salon = Salons::find(Auth::user()->salon_id)) {

            $salon_data = $request->all();

            $salon_update = $this->salon_repo->updateSalon($salon_data, $salon);

            return ['status' => $salon_update['status'], 'message' => $salon_update['message']];
        }

        return ['status' => 0, 'message' => trans('salon.salon_not_found')];

    }

    //if id is supplied return location by id, if id = null return first result
    public function getLocationInfo() {

        $salon_id = Auth::user()->salon_id;
        $salon = Salons::find($salon_id);

        $location = Auth::user()->location_id;
        $country_list = Countries::all();

        if($location != 0) {
            $location = $this->salon_repo->getLocationById($salon_id, $location);

            $time_list = $this->info_repo->getHoursList($salon_id);

            if(empty($location['location']['hours'])) {
                $location['location']['hours'] = null;
            }

            if($location['status'] === 1) {
                return view('salon.locationInfo', ['status' => 1, 'salon' => $salon, 'countries' => $country_list, 'location' => $location['location']['location'][0], 'location_hours' => $location['location']['hours'], 'time_list' => $time_list]);
            }
        } else {
            return redirect()->route('newLocation');
        }

        return view('salon.locationInfo', ['status' => 0]);

    }

    public function newLocation() {

        $country_list = Countries::all();

        return view('salon.locationInfo', ['status' => 0, 'countries' => $country_list]);

    }

    public function createLocation(Request $request) {

        $salon_id = Auth::user()->salon_id;

        $location = $this->salon_repo->createNewLocation($request->all(), $salon_id);

        if($location['status'] === 1) {

            $location_id = $location['location_id'];

            $this->staff_repo->assignLocationToAdmin($location_id);

            return redirect()->route('locationInfo')->with('success_message', trans('salon.location_created'))->with('active_tab', 1);
        }

        return redirect()->route('dashboard')->with('error_message', $location['message']);

    }

    public function updateLocation(Request $request) {

        $location = $this->salon_repo->updateLocation($request->all(), $request->location_id);
        return $location;
        return ['status' => $location['status'], 'message' => $location['message']];

    }

    public function updateHours(Request $request) {

        $working_hours = $this->salon_repo->updateWorkingHours($request->all());

        return ['status' => $working_hours['status'], 'message' => $working_hours['message']];
    }

    public function getSalonServices() {

        if($location = Location::find(Auth::user()->location_id)) {

            $salon = Salons::find(Auth::user()->salon_id);

            Session::put('salon_id', $salon->id);

            $category_list = Category::where('location_id', $location->id)->get();

            $services = $this->salon_repo->getServices($location->id);

            $location_staff = User::where('location_id', $location->id)->get();

            $group_services_arr = [];
            $subgroup_services_arr = [];
            $categories = Category::where('location_id', $location->id)->get();

            foreach($categories as $category) {
                foreach($category->group as $group) {
                    $group_services = Service::where('location_id', $location->id)->where('category', $category->id)->where('group', $group->id)->orderBy('order', 'ASC')->get();
                    foreach($group_services as $group_service) {
                        if($group_service->sub_group === null) {
                            $group_services_arr[] = [
                                'group_id' => $group->id,
                                'service_id' => $group_service->id,
                                'name' => $group_service->service_details->name,
                                'order' => $group_service->order
                            ];
                        } else {
                            $subgroup_services_arr[] = [
                                'group_id' => $group->id,
                                'subgroup_id' => $group_service->sub_group,
                                'service_id' => $group_service->id,
                                'name' => $group_service->service_details->name,
                                'order' => $group_service->order
                            ];
                        }
                    }
                }
            }

            $colors = $this->salon_repo->getColors($location);

            return view('salon.salonService', ['salon' => $salon, 'location' => $location, 'location_staff' => $location_staff, 'colors' => $colors, 'category_list' => $services['categories'], 'group_services' => $group_services_arr, 'subgroup_services' => $subgroup_services_arr]);
        }

        return redirect()->back()->with('error_message', trans('salon.no_locations_added'));

    }

    public function saveService(Request $request) {

        $result = $this->salon_repo->saveSalonService($request->location_id, $request->services);

        if($result["status"] != 0) {
            return redirect()->back()->with('success_message',trans('salon.service_saved'));
        }

        return redirect()->back()->with('error_message',trans('salon.error_updating'))->with("unsaved_data",$result["items_not_saved"]);

    }

    public function updateBilling(Request $request) {


        $validator = Validator::make($request->all(), BillingLocation::$validation_rules);

        if($validator->fails()) {
            return ['status' => 0, 'message' => $validator->errors()->all()[0]];
        }

        $billing = $this->salon_repo->saveLocationBilling($request->all());

        return ['status' => $billing['status'], 'message' => $billing['message']];

    }

    public function deleteLocation() {

        try {

            if($location = Location::find(Auth::user()->location_id)) {

                DB::beginTransaction();
                $location->delete();
                DB::commit();

                $unset_location = $this->staff_repo->unsetLocation(Auth::user()->location_id);

                return redirect()->route('locationInfo')->with('success_message', trans('salon.location_deleted'));
            }

            return redirect()->back()->with('error_message', trans('salon.delete_failed'));

        } catch (Exception $exc) {

            return redirect()->back()->with('error_message', trans('salon.delete_failed'));

        }
    }

    public function getSalonData() {

        if($salon = Salons::find(Auth::user()->salon_id)) {

            $billing = BillingSalon::find($salon->id);

            return ['status' => 1, 'data' => $salon, 'billing' => $billing];

        }

        return ['status' => 0];

    }

    public function addNewCategory(Request $request) {

        if($location = Location::find(Auth::user()->location_id)) {

            $category = $this->salon_repo->saveCategory($location, $request->all());

            if($category['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.category_added'));
            }

        }

        return redirect()->back()->with('error_message', trans('salon.error_updating'));

    }

    public function deleteCategory($id) {

        if($category = Category::find($id)) {

            if($category->location_id == Auth::user()->location_id) {

                $delete_cat = $this->salon_repo->deleteCategory($category);

                if($delete_cat['status'] === 1) {
                    return ['status' => 1];
                }

            }

        } else {
            return ['status' => 0];
        }

    }

    public function addNewSubCategory(Request $request) {

        if($location = Location::find(Auth::user()->location_id)) {

            $sub_category = $this->salon_repo->saveSubCategory($request->all());

            if($sub_category['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.category_added'));
            }

        }

        return redirect()->back()->with('error_message', trans('salon.error_updating'));

    }

    public function deleteSubCategory($id) {

        if($sub_category = SubCategory::find($id)) {
            if($sub_category->get_group->get_category->location_id == Auth::user()->location_id) {

                $delete_cat = $this->salon_repo->deleteSubCategory($sub_category);

                if($delete_cat['status'] === 1) {
                    return ['status' => 1];
                }
            }
        }

        return ['status' => 0];

    }

    public function addNewGroup(Request $request) {

        if($location = Location::find(Auth::user()->location_id)) {

            $group = $this->salon_repo->saveGroup($request->all());

            if($group['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.category_added'));
            }

        }

        return redirect()->back()->with('error_message', trans('salon.error_updating'));

    }

    public function deleteGroup($id) {

        if($group = Group::find($id)) {

            if($group->get_category->location_id == Auth::user()->location_id) {

                try {

                    DB::beginTransaction();
                    $group->delete();
                    DB::commit();

                    return ['status' => 1];

                } catch (Exception $exc) {
                    return ['status' => 0, 'message' => $exc->getMessage()];
                }

            }

        }

        return ['status' => 0, 'message' => ':( error'];

    }

    public function getCategoryInfo($id) {

        if($category = Category::find($id)) {

            if($category->location_id == Auth::user()->location_id) {

                return ['status' => 1, 'category' => $category];

            }
        }

        return ['status' => 0];

    }

    public function getGroupInfo($id) {

        if($group = Group::find($id)) {

            if($group->get_category->location_id == Auth::user()->location_id) {

                return ['status' => 1, 'group' => $group];

            }

        }

        return ['status' => 0];

    }

    public function getSubCategoryInfo($id) {

        if($sub_category = SubCategory::find($id)) {

            if($sub_category->get_group->get_category->location_id == Auth::user()->location_id) {

                return ['status' => 1, 'subcategory' => $sub_category];

            }
        }

        return ['status' => 0];

    }

    public function getGroupList($id) {

        if($category = Category::find($id)) {

            if($category->location_id == Auth::user()->location_id) {
                $group_list = Group::where('category_id', $id)->get();

                return $group_list;
            }
        }

        return ['status' => 0];

    }

    public function getSubGroupList($id) {

        if($group = Group::find($id)) {

            if($group->get_category->location_id == Auth::user()->location_id) {
                $subgroup_list = SubCategory::where('group_id', $id)->get();

                return $subgroup_list;
            }
        }

        return ['status' => 0];

    }

    public function addService(Request $request) {

        $validator = Validator::make($request->all(), Service::$service_validation);

        if ($validator->fails()) {
            return redirect()->back()->with('error_message', $validator->errors()->all()[0]);
        }

        $service = $this->salon_repo->saveService($request->all());

        if($service['status'] === 1) {
            return redirect()->back()->with('success_message', trans('salon.service_saved'));
        }

        return redirect()->back()->with('error_message', $service['message']);
    }

     public function editService(Request $request) {

        if($service = Service::find($request->service_id)) {

            $service_edit = $this->salon_repo->saveService($request->all());

            if($service_edit['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.service_saved'));
            }

        }

        return redirect()->back()->with('error_message', trans('salon.error_updating'));

    }

    public function deleteService($id) {

        if($service = Service::find($id)) {

            if($service->service_category->location_id == Auth::user()->location_id) {

                $service_delete = $this->salon_repo->deleteService($service);

                return $service_delete;

            }
        }

        return ['status' => 0];

    }

    public function editServiceStaff(Request $request) {

        if($service = Service::find($request->service_id)) {

            $staff = $this->salon_repo->saveServiceStaff($request->all());

            if($staff['status'] === 1) {
                return redirect()->back()->with('success_message', trans('salon.staff_added'));
            }

        }

        return redirect()->back()->with('error_message', trans('salon.error_updating'));

    }

    public function getServiceInfo($id) {

        if($service = Service::find($id)) {

            if($service->service_category->location_id == Auth::user()->location_id) {

                $service_info = [
                 'service' => [
                        'category' => $service->category,
                        'group' => $service->group,
                        'subgroup' => $service->sub_group,
                        'order' => $service->order,
                        'allow_discounts' => $service->allow_discounts,
                        'award_points' => $service->award_points,
                        'points_awarded' => $service->points_awarded
                    ],
                    'service_details' => [
                        'name' => $service->service_details->name,
                        'description' => $service->service_details->description,
                        'code' => $service->service_details->code,
                        'barcode' => $service->service_details->barcode,
                        'service_length' => date('H:i', strtotime($service->service_details->service_length)),
                        'price_no_vat' => $service->service_details->price_no_vat,
                        'vat' => $service->service_details->vat,
                        'price' => $service->service_details->base_price,
                        'available' => $service->service_details->available,
                        'all_staff' => $service->service_details->all_staff
                    ]
                ];

                return ['status' => 1, 'service' => $service_info];
            }
        }

        return ['status' => 0];

    }

    public function serviceStaff($id) {

        if($service = Service::find($id)) {

            if($service->service_category->location_id == Auth::user()->location_id) {

                $service_staff = ServiceStaff::select('user_id')->where('location_id', Auth::user()->location_id)->where('service_id', $id)->get();

                $users = User::where('location_id', Auth::user()->location_id)->get();

                $location_staff = [];

                foreach($users as $user) {
                    if(!$user->hasRole('salonadmin')) {
                        $selected = 0;
                        foreach($service_staff as $staff) {
                            if($user->id === $staff->user_id) {
                                $selected = 1;
                                break 1;
                            }
                        }
                        $location_staff[] = [
                            'id' => $user->id,

                            'first_name' => $user->user_extras->first_name,
                            'last_name' => $user->user_extras->last_name,
                            'selected' => $selected
                        ];
                    }
                }

                return $location_staff;
            }
        }
    }

    public function changeServiceOrder(Request $request) {

        $order = $this->salon_repo->changeOrder($request->all());

        if($order['status'] != 0) {
            return redirect()->back()->with('success_message', trans('salon.updated_successfuly'));
        }

        return redirect()->back()->with('error_message', trans('salon.error_updating'));

    }

    public function getUniqueCodes() {

        $location = Location::find(Auth::user()->location_id);

        $codes = $this->salon_repo->getUniqueCodes($location);

        if($codes['status'] === 1) {
            return ['code' => $codes['code'], 'barcode' => $codes['barcode']];
        }

        return ['status' => 0];

    }

    public function addNewField(Request $request) {

        $fields = $this->salon_repo->saveFields($request->all());

        if($fields['status'] === 1) {
            return ['status' => 1, 'message' => $fields['message'], 'field' => $fields['field']];
        }
        return ['status' => 0, 'message' => $fields['message']];

    }

    public function getFieldInfo($field_id) {

        if($field = CustomFields::find($field_id)) {

            return ['status' => 1, 'field_name' => $field->field_title, 'field_type' => $field->field_type, 'select_options' => $field->select_options];

        }

        return ['status' => 0];

    }

    public function updateCustomField(Request $request) {

        $field_update = $this->salon_repo->updateCustomFields($request->all());

        if($field_update['status'] === 1) {
            return ['status' => 1, 'message' => $field_update['message'], 'field' => $field_update['field']];
        }
        return ['status' => 0, 'message' => $field_update['message']];

    }

    public function deleteField(Request $request) {

        $field_delete = $this->salon_repo->deleteField($request->all());

        if($field_delete['status'] === 1) {
            return ['status' => 1];
        }

        return ['status' => 0, 'message' => $field_delete['message']];

    }

    public function getLocationServices($id) {
        if($location = Location::find($id)) {

            $services_by_category = $this->salon_repo->getServicesByCategory($location);

            if($services_by_category['status'] != 1) {
                return ['status' => 0, 'message' => $services_by_category['message']];
            }

            return ['status' => 1, 'services' => $services_by_category['services']];
        }
    }

    public function uploadLocationImages(Request $request) {

        $image_upload = $this->salon_repo->uploadImage($request->file);

        if($image_upload['status'] === 1) {
            return ['status' => 1];
        }

        return response($image_upload['message'], 500)
            ->header('Content-Type', 'text/plain');

    }

    public function deleteLocationPhoto(Request $request) {

        $photo_delete = $this->salon_repo->deleteLocationPhoto($request->id);

        if($photo_delete['status'] === 1) {
            return ['status' => 1, 'message' => $photo_delete['message']];
        }

        return ['status' => 0, 'message' => $photo_delete['message']];

    }

    public function getServiceGroups($id) {

        $groups = $this->salon_repo->getServiceGroups($id);

        if($groups['status'] === 1) {
            return ['status' => 1, 'group' => $groups['group']];
        }

        return ['status' => 0, 'message' => $groups['message']];

    }

    public function getSalonManagement() {

        $salons = Salons::all();

        return view('superadmin.salonsManagement', ['salons' => $salons]);

    }

    public function importServices(Request $request) {

        $import = $this->salon_repo->importServices($request->all());
        return $import;
        if($import['status'] === 1) {
            return redirect()->back()->with('success_message', trans('salon.services_imported'));
        }

        return redirect()->back()->with('error_message', trans('salon.import_failed'));
    }
}
