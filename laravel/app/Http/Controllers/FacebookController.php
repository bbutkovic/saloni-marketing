<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\{InfoRepository,UserRepository,SalonRepository,StaffRepository};
use App\Models\Salons;
use App\Models\{Languages,Location,Countries};
use Illuminate\Support\Facades\{
    Hash, Session, DB, Auth, Input
};
use App;
use App\{User,Role,Permission,PermissionRole};
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\TargetingSearch;
use FacebookAds\Object\Search\TargetingSearchTypes;
use FacebookAds\Object\Targeting;
use FacebookAds\Object\Fields\TargetingFields;
use DateTime;
use FacebookAds\Object\AdSet;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Values\AdSetBillingEventValues;
use FacebookAds\Object\Values\AdSetOptimizationGoalValues;
use FacebookAds\Object\Values\InsightsPresets;
use FacebookAds\Object\AdCampaign;
use FacebookAds\Object\AdImage;
use FacebookAds\Object\Fields\AdImageFields;
use FacebookAds\Object\AdCreative;
use Intervention\Image\Facades\Image;


class FacebookController extends Controller
{
    public function getFacebookCampaignManagement() {
        return view('campaigns.facebook');
    }

    public function createFacebookCampaign(Request $request) {

        $app_id = "387717418355900";
        $app_secret = "80aa1bae3834f884a29cdd5ed6ff198c";
        $access_token = $request->access_token;
        $id = 'act_175148547';

        $api = Api::init($app_id, $app_secret, $access_token);
        $api->setLogger(new CurlLogger());

        /*$result = TargetingSearch::search(TargetingSearchTypes::INTEREST,
            null,
            'hair care');
        echo '<pre>'; print_r($result; exit;*/

        $fields = array(
        );
        $params = array(
            'name' => $request->name,
            'objective' => 'LINK_CLICKS',
            'status' => 'PAUSED',
        );

        $campaign = (new AdAccount($id))->createCampaign($fields, $params);
        $campaign_id = $campaign->id;
        if(is_object(($campaign))) {
            $result = TargetingSearch::search(TargetingSearchTypes::INTEREST,
                null,
                'hair care');
            $targeting = new Targeting();
            $targeting->{TargetingFields::GEO_LOCATIONS} =
                array(
                    'countries' => ['US','DE','HR']
                );

            $result = TargetingSearch::search(
                TargetingSearchTypes::INTEREST,
                null,
                'baseball');

            $targeting->{TargetingFields::AGE_MAX} = '50';
            $targeting->{TargetingFields::AGE_MIN} = '20';
            $targeting->{TargetingFields::GENDERS} = ['1'];

            $start_time = (new \DateTime("+1 week"))->format(DateTime::ISO8601);
            $end_time = (new \DateTime("+2 week"))->format(DateTime::ISO8601);

            //create adset -> audience targeting set
            $adset = new AdSet(null, $id);
            $adset->setData(array(
                AdSetFields::NAME => 'My Ad Set 3321',
                AdSetFields::OPTIMIZATION_GOAL => AdSetOptimizationGoalValues::REACH,
                AdSetFields::BILLING_EVENT => AdSetBillingEventValues::IMPRESSIONS,
                AdSetFields::BID_AMOUNT => 2,
                AdSetFields::DAILY_BUDGET => 1000,
                AdSetFields::CAMPAIGN_ID => $campaign_id,
                AdSetFields::TARGETING => $targeting,
                AdSetFields::START_TIME => $start_time,
                AdSetFields::END_TIME => $end_time,
            ));

            $adset->create(array(
                AdSet::STATUS_PARAM_NAME => AdSet::STATUS_PAUSED,
            ));

            //save image
            $ad_image = Image::make($request->ad_image);
            $mime_type = $request->ad_image->getClientOriginalExtension();
            $image_name = substr(md5(rand()), 0, 15) . '.' . $mime_type;
            $ad_image->save(public_path() . '/images/salon-logo/' . $image_name);

            //create ad image
            $image = new AdImage(null, $id);
            $image->{AdImageFields::FILENAME} = public_path() . '/images/salon-logo/' . $image_name;
            $image->create();
            $image_hash = $image->{AdImageFields::HASH}.PHP_EOL;
            $fields = array(
            );
            $creative_params = array(
                'name' => 'Sample Creative',
                'object_story_spec' => array('page_id' => '685730971786169','link_data' => array('image_hash' => $image_hash,'link' => 'https://facebook.com/685730971786169','message' => 'try it out')),
            );
            $creative = (new AdAccount($id))->createAdCreative($fields, $creative_params);

            //create ad
            $fields = array(
            );
            $ad_params = array(
                'name' => 'My Ad',
                'adset_id' => $adset->id,
                'creative' => array('creative_id' => $creative->id),
                'status' => 'PAUSED',
            );
            $new_ad = (new AdAccount($id))->createAd($fields, $ad_params);

            return 1;
        }

        return ['status' => 0, 'message' => trans('salon.campaign_error')];
    }
}