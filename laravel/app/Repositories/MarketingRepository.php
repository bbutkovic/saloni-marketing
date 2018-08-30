<?php

namespace App\Repositories;

use App\Models\Salon\MarketingCampaign;
use App\Notifications\EmailCampaign;
use Illuminate\Support\Facades\Auth;
use App\Models\{Location,Salons};
use App\Models\Marketing\{MarketingTemplate,Reminders};
use App\Models\Salon\{LoyaltyManagement,LoyaltyDiscounts,Service,HappyHour,Vouchers,LoyaltyPrograms};

class MarketingRepository {

    public function createNewTemplate($data) {
        try {
            $template = new MarketingTemplate;
            $template->location_id = Auth::user()->location_id;
            $template->template_for = $data['template_for'];
            $template->template_type = $data['template_type'];
            $template->template_name = $data['template_name'];
            $template->subject = $data['template_subject'];
            if($data['template_for'] != 1) {
                $content = strip_tags($data['editordata'], '<br>');
            } else {
                $content = $data['editordata'];
            }
            $template->content = $content;
            $template->save();

            return ['status' => 1, 'message' => trans('salon.template_created')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }
    }

    public function getMarketingTemplates($location, $campaign) {

        if(!Auth::user()->hasRole('superadmin')) {
            if (!$campaign) {
                $templates = MarketingTemplate::where('location_id', $location->id)->get();
            } else {
                $templates = MarketingTemplate::where('location_id', $location->id)->where('template_type', 8)->get();
            }
        } else {
            $templates = MarketingTemplate::where('created_by', 'superadmin')->get();
        }
        $templates_list = [];

        foreach($templates as $template) {
            $templates_list[] = [
                'id' => $template->id,
                'location_id' => $template->location_id,
                'template_for' => $template->template_for,
                'template_for_str' => $this->getForStr($template->template_for),
                'template_type' => $template->template_type,
                'template_type_str' => $this->getTypeStr($template->template_type),
                'template_name' => $template->template_name,
                'template_subject' => $template->subject
            ];
        }

        return $templates_list;

    }

    public function getTypeStr($id) {

        $str = '';

        switch($id) {
            case 1:
                $str = trans('salon.template_appointment_reminders');
                break;
            case 2:
                $str = trans('salon.template_confirmation');
                break;
            case 3:
                $str = trans('salon.template_reschedules');
                break;
            case 4:
                $str = trans('salon.template_cancelations');
                break;
            case 5:
                $str = trans('salon.template_birthday');
                break;
            case 6:
                $str = trans('salon.template_loyalty_points');
                break;
            case 7:
                $str = trans('salon.template_new_client');
                break;
        }

        return $str;

    }

    public function getForStr($id) {

        $str = '';

        switch($id) {
            case 1:
                $str = 'Email';
                break;
            case 2:
                $str = 'SMS';
                break;
            case 3:
                $str = 'Viber';
                break;
            case 4:
                $str = 'Facebook messenger';
                break;
            case 5:
                $str = 'Push notification';
                break;
        }

        return $str;

    }

    public function getTemplate($id) {

        if($template = MarketingTemplate::find($id)) {
            return ['status' => 1, 'template' => $template];
        }

        return ['status' => 0, 'message' => trans('salon.template_not_found')];

    }

    public function editTemplate($data) {

        if($template = MarketingTemplate::find($data['id'])) {
            try {

                $template->template_name = $data['name'];
                $template->subject = $data['subject'];
                $template->template_for = $data['temp_for'];
                $template->template_type = $data['temp_type'];
                if($data['temp_for'] != 1) {
                    $content = strip_tags($data['content'], '<br>');
                } else {
                    $content = $data['content'];
                }
                $template->content = $content;
                $template->save();

                $template_obj[] = [
                    'id' => $template->id,
                    'template_for_str' => $this->getForStr($template->template_for),
                    'template_type_str' => $this->getTypeStr($template->template_type),
                    'template_name' => $template->template_name,
                ];

                return ['status' => 1, 'message' => trans('salon.updated_successfuly'), 'template' => $template_obj];

            } catch (Exception $exc) {
                return ['status' => 0, 'message' => $exc->getMessage()];
            }
        }

        return ['status' => 0, 'message' => trans('salon.template_not_found')];

    }

    public function deleteTemplate($id) {

        if($template = MarketingTemplate::find($id)) {
            try {
                $template->delete();
                return ['status' => 1, 'message' => trans('salon.deleted_successfully')];
            } catch (Exception $exc) {
                return ['status' => 0, 'message' => $exc->getMessage()];
            }
        }

        return ['status' => 0, 'message' => trans('salon.template_not_found')];

    }

    public function updateTemplate($data) {

        if($location = Location::find(Auth::user()->location_id)) {

            try {

                $reminder = Reminders::where('location_id', $location->id)->where('reminder_type', $data['type'])->first();
                if($reminder === null) {
                    $reminder = new Reminders;
                }

                if($data['no_notifications'] == 0) {
                    $status = 0;
                    $email_template = null;
                    $sms_template = null;
                    $viber_template = null;
                    $messenger_template = null;
                    $push_template = null;
                } else {
                    $status = 1;
                    $email_template = isset($data['email_temp']) ? $data['email_temp'] : null;
                    $sms_template = isset($data['sms_temp']) ? $data['sms_temp'] : null;
                    $viber_template = isset($data['viber_temp']) ? $data['viber_temp'] : null;
                    $messenger_template = isset($data['messenger_temp']) ? $data['messenger_temp'] : null;
                    $push_template = isset($data['push_temp']) ? $data['push_temp'] : null;
                }

                $reminder->location_id = $location->id;
                $reminder->reminder_type = $data['type'];
                $reminder->reminder_status = $status;
                $reminder->email_template = $email_template;
                $reminder->sms_template = $sms_template;
                $reminder->viber_template = $viber_template;
                $reminder->messenger_template = $messenger_template;
                $reminder->gift_voucher = $data['voucher'];
                $reminder->send_before = $data['send_before'];
                $reminder->save();

                return ['status' => 1, 'message' => trans('salon.updated_successfuly')];

            } catch (Exception $exc) {
                return ['status' => 0, 'message' => $exc->getMessage()];
            }

        }

        return ['status' => 0, 'message' => trans('salon.location_not_found')];

    }

    public function createNewCampaign($data) {

        try {

            if(isset($data['campaign_id'])) {
                $campaign = MarketingCampaign::find($data['campaign_id']);
            } else {
                $campaign = new MarketingCampaign;
            }
            $campaign->location_id = Auth::user()->location_id;
            $campaign->email_template = $data['email_temp'] ?? 0;
            $campaign->sms_template = $data['sms_temp'] ?? 0;
            $campaign->viber_template = $data['viber_temp'] ?? 0;
            $campaign->messenger_template = $data['messenger_temp'] ?? 0;
            $campaign->name = $data['campaign_name'];
            $campaign->inactivity = $data['clients_inactive'];
            $campaign->gender = $data['clients_gender'];
            $campaign->older_than = explode(';', $data['client_age'])[0];
            $campaign->younger_than = explode(';', $data['client_age'])[1];
            $campaign->with_label = $data['with_label'] ?? 0;
            $campaign->with_referral = $data['with_referral'] ?? 0;
            $campaign->with_staff = isset($data['with_staff']) ? implode(',', $data['with_staff']) : 0;
            $campaign->with_category = isset($data['with_category']) ? implode(',', $data['with_category']) : 0;
            $campaign->with_service = isset($data['with_service']) ? implode(',', $data['with_service']) : 0;
            $campaign->campaign_frequency = $data['campaign_frequency'];
            $campaign->campaign_time = $data['campaign_time'];
            $campaign->loyalty_points = isset($data['loyalty_points']) ? $data['loyalty_points'] : null;
            $campaign->gift_voucher = $data['voucher'] ?? null;
            $campaign->save();

            return ['status' => 1, 'id' => $campaign->id];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function getCampaignInfo($id) {

        $campaign = MarketingCampaign::find($id);

        $staff = explode(',', $campaign->with_staff);
        $category = explode(',', $campaign->with_category);
        $service = explode(',', $campaign->with_service);

        $campaign_info = [
            'campaign' => $campaign,
            'staff' => $staff,
            'category' => $category,
            'service' => $service
        ];

        if($campaign != null) {
            return ['status' => 1, 'info' => $campaign_info];
        }

        return ['status' => 0, trans('salon.campaign_not_found')];

    }

    public function deleteCampaign($id) {

        try {

            $campaign = MarketingCampaign::find($id);
            $campaign->delete();

            return ['status' => 1, 'message' => trans('salon.deleted_successfully')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

    public function sendCampaign($client_list, $campaign) {

        try {

            foreach($client_list as $client) {

                if ($client->email_marketing === 1 && $campaign->email_template != 0) {
                    $client->notify(new EmailCampaign($client, $campaign));
                }

                if ($client->sms_marketing === 1 && $campaign->sms_template != 0) {
                    //
                }

                if ($client->viber_marketing === 1 && $campaign->viber_template != 0) {
                    //
                }

                if ($client->facebook_marketing === 1 && $campaign->messenger_template != 0) {
                    //
                }

            }

            return ['status' => 1, 'message' => trans('salon.campaign_sent')];

        } catch (Exception $exc) {
            return ['status' => 0, 'message' => $exc->getMessage()];
        }

    }

}