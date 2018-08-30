<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\{InfoRepository,UserRepository,SalonRepository,StaffRepository};
use App\Models\Salons;
use App\Models\{Languages,Location,Countries};
use Illuminate\Support\Facades\{Session,DB,Auth,Input};
use App;
use App\{User,Role,Permission,PermissionRole};
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;

class FacebookController extends Controller
{
    public function getFacebookCampaignManagement() {
        return view('campaigns.facebook');
    }

    public function createFacebookCampaign(Request $request) {
        $app_id = "387717418355900";
        $app_secret = "80aa1bae3834f884a29cdd5ed6ff198c";
        $access_token = $request->access_token[0]['token'];
        $id = 'act_175148547';

        $api = Api::init($app_id, $app_secret, $access_token);
        $api->setLogger(new CurlLogger());

        $fields = array(
        );
        $params = array(
            'name' => $request->name,
            'objective' => 'LINK_CLICKS',
            'status' => 'PAUSED',
        );
        return json_encode((new AdAccount($id))->createCampaign(
            $fields,
            $params
        )->getResponse()->getContent(), JSON_PRETTY_PRINT);
    }
}