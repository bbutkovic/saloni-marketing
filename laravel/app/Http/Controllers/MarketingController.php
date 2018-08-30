<?php

namespace App\Http\Controllers;

use App\Models\Clients\ClientLabels;
use App\Models\Clients\ClientReferrals;
use App\Models\Salon\MarketingCampaign;
use App\Repositories\ClientRepository;
use App\Repositories\InfoRepository;
use App\Repositories\MarketingRepository;
use App\Models\{Location};
use App\Models\Marketing\Reminders;
use App\Models\Salon\Vouchers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketingController extends Controller
{
    public function __construct() {
        $this->marketing_repo = new MarketingRepository;
        $this->info_repo = new InfoRepository;
        $this->client_repo = new ClientRepository;
    }

    public function getMarketingSettings()
    {

        $location = Location::find(Auth::user()->location_id);

        $templates = $this->marketing_repo->getMarketingTemplates($location, false);

        $time_list = $this->info_repo->getHoursList(2);

        if (!Auth::user()->hasRole('superadmin')) {
            $reminders = Reminders::where('location_id', $location->id)->get();

            $vouchers = Vouchers::where('location_id', $location->id)->get();

            $labels = ClientLabels::where('salon_id', Auth::user()->salon_id)->orWhere('salon_id', 'all')->get();

            $referrals = ClientReferrals::where('salon_id', Auth::user()->salon_id)->get();

            return view('salon.marketingSettings', ['location' => $location, 'templates' => $templates, 'reminders' => $reminders, 'vouchers' => $vouchers, 'labels' => $labels, 'referrals' => $referrals, 'time_list' => $time_list]);
        }

        return view('salon.marketingSettings', ['templates' => $templates, 'time_list' => $time_list]);
    }

    public function createNewTemplate(Request $request) {

        $new_template = $this->marketing_repo->createNewTemplate($request->all());

        if($new_template['status'] === 1) {
            return redirect()->back()->with('success_message', $new_template['message']);
        }

        return redirect()->back()->with('error_message', $new_template['message']);
    }

    public function getTemplate($id) {

        $template = $this->marketing_repo->getTemplate($id);

        if($template['status'] === 1) {
            return ['status' => 1, 'template' => $template['template']];
        }

        return ['status' => 0, 'message' => $template['message']];

    }

    public function editTemplate(Request $request) {

        $template_update = $this->marketing_repo->editTemplate($request->all());

        if($template_update['status'] === 1) {
            return ['status' => 1, 'message' => $template_update['message'], 'template' => $template_update['template']];
        }

        return ['status' => 0, 'message' => $template_update['message']];

    }

    public function deleteTemplate(Request $request) {

        $template_delete = $this->marketing_repo->deleteTemplate($request->id);

        return ['status' => $template_delete['status'], 'message' => $template_delete['message']];

    }

    public function updateReminder(Request $request) {

        $reminder_update = $this->marketing_repo->updateTemplate($request->all());

        return ['status' => $reminder_update['status'], 'message' => $reminder_update['message']];

    }

    public function getNewCampaign() {

        $location = Location::find(Auth::user()->location_id);

        $vouchers = Vouchers::where('location_id', $location->id)->get();

        $labels = ClientLabels::where('salon_id', Auth::user()->salon_id)->orWhere('salon_id', 'all')->get();

        $referrals = ClientReferrals::where('salon_id', Auth::user()->salon_id)->get();

        $time_list = $this->info_repo->getHoursList(Auth::user()->salon_id);

        $staff = User::where('location_id', $location->id)->get();

        $templates = $this->marketing_repo->getMarketingTemplates($location, true);

        return view('salon.marketing.campaign', ['location' => $location, 'vouchers' => $vouchers, 'templates' => $templates, 'labels' => $labels, 'referrals' => $referrals, 'time_list' => $time_list, 'staff' => $staff]);
    }

    public function createNewCampaign(Request $request) {
        $new_campaign = $this->marketing_repo->createNewCampaign($request->all());

        if($new_campaign['status'] === 1) {
            return redirect()->route('getCampaignEdit', $new_campaign['id'])->with('success_message', trans('salon.campaign_created'));
        }

        return redirect()->back()->with('error_message', trans('salon.error_updating'));

    }

    public function getCampaignInfo($id) {

        $info = $this->marketing_repo->getCampaignInfo($id);

        $location = Location::find(Auth::user()->location_id);

        $vouchers = Vouchers::where('location_id', $location->id)->get();

        $labels = ClientLabels::where('salon_id', Auth::user()->salon_id)->orWhere('salon_id', 'all')->get();

        $referrals = ClientReferrals::where('salon_id', Auth::user()->salon_id)->get();

        $time_list = $this->info_repo->getHoursList(Auth::user()->salon_id);

        $templates = $this->marketing_repo->getMarketingTemplates($location, true);

        $staff = User::where('location_id', $location->id)->get();

        if($info['status'] === 1) {
            return view('salon.marketing.editCampaign', ['location' => $location, 'vouchers' => $vouchers, 'templates' => $templates, 'labels' => $labels, 'referrals' => $referrals, 'time_list' => $time_list, 'staff' => $staff, 'info' => $info['info']]);
        }

        return redirect()->back()->with('error_message', $info['message']);

    }

    public function deleteCampaign($id) {

        $delete = $this->marketing_repo->deleteCampaign($id);

        return ['status' => $delete['status'], 'message' => $delete['message']];

    }

    public function sendCampaign($id) {

        $campaign = MarketingCampaign::find($id);

        if(Auth::user()->location_id == $campaign->location_id) {

            $client_selection = $this->client_repo->getClientSelection($campaign->clients_inactive, $campaign->clients_gender, $campaign->older_than, $campaign->younger_than, $campaign->with_label, $campaign->with_referral, $campaign->with_staff, $campaign->with_category, $campaign->with_service, $campaign->loyalty_points);

            $send_campaign = $this->marketing_repo->sendCampaign($client_selection, $campaign);

            return ['status' => $send_campaign['status'], 'message' => $send_campaign['message']];

        }

    }

}
