<?php

namespace App\Repositories;

use FacebookAds\Exception\Exception;
use Illuminate\Support\Facades\Auth;
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

class FacebookRepository {

    public function createNewFacebookCampaign($data) {

        try {
            $app_id = "387717418355900";
            $app_secret = "80aa1bae3834f884a29cdd5ed6ff198c";
            $access_token = $data['access_token'];
            $content = json_decode(file_get_contents('https://graph.facebook.com/v3.0/me/adaccounts?access_token='.$access_token));
            $id = $content->data[0]->id;

            $api = Api::init($app_id, $app_secret, $access_token);
            $api->setLogger(new CurlLogger());

            $fields = array(
            );
            $params = array(
                'name' => $data['name'],
                'objective' => $data['objective'],
                'status' => $data['status'],
            );

            $campaign = (new AdAccount($id))->createCampaign($fields, $params);
            $campaign_id = $campaign->id;

            if(is_object(($campaign))) {
                $targeting = new Targeting();
                $cities_arr = [];
                $countries_arr = [];
                $interests_arr = [];
                if($data['audience_cities'] != null) {
                    $cities_arr = $this->createCitiesTargeting($cities_arr, $data['audience_cities']);
                }
                if($data['audience_cities'] === null && $data['audience_countries'] != null) {
                    $countries_arr = $this->createCountriesTargeting($countries_arr, $data['audience_countries']);
                }
                if($data['audience_interests'] != null) {
                    $interests_arr = $this->createInterestsTargeting($interests_arr, $data['audience_interests']);
                }
                $targeting->{TargetingFields::GEO_LOCATIONS} =
                    array(
                        'countries' => $countries_arr,
                        'cities' => $cities_arr
                    );
                $targeting->{TargetingFields::INTERESTS} = $interests_arr;
                $targeting->{TargetingFields::AGE_MAX} = '50';
                $targeting->{TargetingFields::AGE_MIN} = '20';
                $targeting->{TargetingFields::GENDERS} = ['1'];
                $targeting->{TargetingFields::PUBLISHER_PLATFORMS} = [
                    'facebook',
                    'audience_network'
                ];

                $start_time = (new \DateTime("+1 week"))->format(DateTime::ISO8601);
                $end_time = (new \DateTime("+2 week"))->format(DateTime::ISO8601);

                //create adset -> audience targeting set
                $adset = new AdSet(null, $id);
                $adset->setData(array(
                    AdSetFields::NAME => $data['adset_name'],
                    AdSetFields::OPTIMIZATION_GOAL => AdSetOptimizationGoalValues::REACH,
                    AdSetFields::BILLING_EVENT => AdSetBillingEventValues::IMPRESSIONS,
                    AdSetFields::BID_AMOUNT => $data['bid'] * 100,
                    AdSetFields::DAILY_BUDGET => $data['budget'] * 100,
                    AdSetFields::CAMPAIGN_ID => $campaign_id,
                    AdSetFields::TARGETING => $targeting,
                    AdSetFields::START_TIME => $start_time,
                    AdSetFields::END_TIME => $end_time,
                ));

                $adset->create(array(
                    AdSet::STATUS_PARAM_NAME => AdSet::STATUS_PAUSED,
                ));

                //save image
                $ad_image = Image::make($data['ad_image']);
                $mime_type = $data['ad_image']->getClientOriginalExtension();
                $image_name = substr(md5(rand()), 0, 15) . '.' . $mime_type;
                $ad_image->save(public_path() . '/images/salon-logo/' . $image_name);

                //create ad image
                $image = new AdImage(null, $id);
                $image->{AdImageFields::FILENAME} = public_path() . '/images/salon-logo/' . $image_name;
                $image->create();

                $image_hash = $image->{AdImageFields::HASH} . PHP_EOL;
                $fields = array();

                $creative_params = array(
                    'name' => $data['ad_title'],
                    'object_story_spec' => array(
                        'page_id' => $data['facebook_page_id'],
                        'link_data' => array(
                            'image_hash' => $image_hash,
                            'link' => $data['redirect_link'],
                            'message' => $data['ad_message']
                        )
                    ),
                );
                $creative = (new AdAccount($id))->createAdCreative($fields, $creative_params);
                //create ad
                $fields = array();
                $ad_params = array(
                    'name' => $data['ad_title'],
                    'adset_id' => $adset->id,
                    'creative' => array('creative_id' => $creative->id),
                    'status' => 'PAUSED',
                );

                $new_ad = (new AdAccount($id))->createAd($fields, $ad_params);

                return 1;
            }

        } catch (Exception $e) {
            return ['status' => 0, 'message_1' => $e->getErrorUserMessage(), 'message_2' => $e->getErrorUserTitle()];
        }
    }

    public function createCitiesTargeting($arr, $cities_str) {
        $cities = explode(', ', $cities_str);
        foreach($cities as $city) {
            $result = TargetingSearch::search(
                TargetingSearchTypes::GEOLOCATION,
                null,
                $city,
                array(
                    'location_types' => array('city'),
                )
            );

            $targeting_result = json_decode($result->getLastResponse()->getBody());
            $key = $targeting_result->data[0]->key;

            if($key != null) {
                $arr[] = [
                    'key' => $key,
                    'radius' => 50,
                    'distance_unit' => 'kilometer'
                ];
            }
        }
        return $arr;
    }

    public function createCountriesTargeting($arr, $countries_str) {
        $countries = explode(', ', $countries_str);
        foreach($countries as $country) {
            $result = TargetingSearch::search(
                TargetingSearchTypes::GEOLOCATION,
                null,
                $country,
                array(
                    'location_types' => array('country'),
                )
            );

            $targeting_result = json_decode($result->getLastResponse()->getBody());

            $key = $targeting_result->data[0]->key;

            if($key != null) {
                $arr[] = $key;
            }
        }
        return $arr;
    }

    public function createInterestsTargeting($arr, $interests_str) {
        $interests = explode(', ', $interests_str);
        foreach($interests as $interest) {
            $result = TargetingSearch::search(
                TargetingSearchTypes::INTEREST,
                null,
                $interest
            );

            $targeting_results = json_decode($result->getLastResponse()->getBody());

            foreach($targeting_results->data as $targeting_result) {
                $arr[] = [
                    'id' => $targeting_result->id,
                    'name' => $targeting_result->name
                ];
            }
        }
        return $arr;
    }

}