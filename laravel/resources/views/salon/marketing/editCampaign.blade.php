@extends('main')

@section('styles')
    {{ HTML::style('css/plugins/bootstrapselect/bootstrapselect.css') }}
    {{ HTML::style('css/plugins/ionslider/ion.rangeSlider.css') }}
    {{ HTML::style('css/plugins/ionslider/ion.rangeSlider.skinFlat.css') }}
@endsection

@section('scripts')
    {{ HTML::script('js/plugins/bootstrapselect/bootstrapselect.js') }}
    {{ HTML::script('js/marketing/marketing.js') }}
    {{ HTML::script('js/marketing/campaign.js') }}
    {{ HTML::script('js/plugins/ionslider/ion.rangeSlider.min.js') }}
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=944zbcd6b70j3spki3txrzecsz6n99ua5dapocup4abxci3c"></script>
@endsection

@section('content')

    <div class="wrapper wrapper-content">
        <div class="ibox-content">

            {{ Form::open(array('route' => 'addNewCampaign', 'id' => 'addNewCampaignForm')) }}
            {{ Form::hidden('campaign_id', $info['campaign']['id']) }}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="text-muted">{{ trans('salon.marketing_campaign_details') }}</h3>
                        <div class="row campaign-medium-type m-t">
                            <div class="col-sm-7">
                                <hr>
                                <div class="form-group col-md-6">
                                    <label for="emailNotifications" class="text-muted">Email</label>
                                    <input type="checkbox" class="checkbox-email-notifications1 checkbox-reminder1" name="email_notify" @if(isset($info['campaign']) && $info['campaign']['email_template'] != 0) checked @endif>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="smsNotifications" class="text-muted">SMS</label>
                                    <input type="checkbox" class="checkbox-sms-notifications1 checkbox-reminder1" name="sms_notify" @if(isset($info['campaign']) && $info['campaign']['sms_template'] != 0) checked @endif>
                                </div>
                                <div class="form-group col-md-6" class="text-muted">
                                    <label for="viberNotifications">Viber</label>
                                    <input type="checkbox" class="checkbox-viber-notifications1 checkbox-reminder1" name="viber_notify" @if(isset($info['campaign']) && $info['campaign']['viber_template'] != 0) checked @endif>
                                </div>
                                <div class="form-group col-md-6" class="text-muted">
                                    <label for="messengerNotifications">Facebook Messenger</label>
                                    <input type="checkbox" class="checkbox-messenger-notifications1 checkbox-reminder1" name="messenger_notify" @if(isset($info['campaign']) && $info['campaign']['messenger_template'] != 0) checked @endif>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4 template-selection-wrap1 @if(!isset($info['campaign']) || $info['campaign']['email_template'] === 0) hidden @endif">
                                <div class="email-template1">
                                    <h5 class="text-muted">{{ trans('salon.select_email_template') }}</h5>
                                    <select class="email-template-select1 form-control" name="email_temp">
                                        <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                        @foreach($templates as $template)
                                            @if($template['template_for'] === 1)
                                                <option value="{{ $template['id'] }}" @if($info['campaign']['email_template'] === $template['id']) selected @endif>{{ $template['template_name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 template-selection-wrap1 @if(!isset($info['campaign']) || $info['campaign']['sms_template'] === 0) hidden @endif">
                                <div class="sms-template1">
                                    <h5 class="text-muted">{{ trans('salon.select_sms_template') }}</h5>
                                    <select class="email-template-select1 form-control" name="sms_temp">
                                        <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                        @foreach($templates as $template)
                                            @if($template['template_for'] === 2)
                                                <option value="{{ $template['id'] }}" @if($info['campaign']['sms_template'] === $template['id']) selected @endif>{{ $template['template_name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 template-selection-wrap1 @if(!isset($info['campaign']) || $info['campaign']['viber_template'] === 0) hidden @endif">
                                <div class="viber-template1">
                                    <h5 class="text-muted">{{ trans('salon.select_viber_template') }}</h5>
                                    <select class="email-template-select1 form-control" name="viber_temp">
                                        <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                        @foreach($templates as $template)
                                            @if($template['template_for'] === 3)
                                                <option value="{{ $template['id'] }}" @if($info['campaign']['viber_template'] === $template['id']) selected @endif>{{ $template['template_name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4 template-selection-wrap1 @if(!isset($info['campaign']) || $info['campaign']['messenger_template'] === 0) hidden @endif">
                                <div class="messenger-template1">
                                    <h5 class="text-muted">{{ trans('salon.select_messenger_template') }}</h5>
                                    <select class="email-template-select1 form-control" name="messenger-temp">
                                        <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                        @foreach($templates as $template)
                                            @if($template['template_for'] === 4)
                                                <option value="{{ $template['id'] }}" @if($info['campaign']['messenger_template'] === $template['id']) selected @endif>{{ $template['template_name'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="campaignName">{{ trans('salon.campaign_name') }}</label>
                            {{ Form::text('campaign_name', $info['campaign']['name'], array('id' => 'campaignName', 'class' => 'form-control', 'required')) }}
                        </div>
                        <hr>
                        <div class="form-group">
                            <label for="campaignFrequency">{{ trans('salon.when_to_send_emails') }}</label>
                            <select name="campaign_frequency" id="campaignFrequency" class="form-control campaign-frequency">
                                <option value="0" @if($info['campaign']['campaign_frequency'] === 0) selected @endif>{{ trans('salon.only_once') }}</option>
                                <option value="1 week" @if($info['campaign']['campaign_frequency'] === '1 week') selected @endif>{{ trans('salon.every_week') }}</option>
                                <option value="2 week" @if($info['campaign']['campaign_frequency'] === '2 week') selected @endif>{{ trans('salon.every_second_week') }}</option>
                                <option value="1 month" @if($info['campaign']['campaign_frequency'] === '1 month') selected @endif>{{ trans('salon.every_month') }}</option>
                                <option value="2 month" @if($info['campaign']['campaign_frequency'] === '2 month') selected @endif>{{ trans('salon.every_two_months') }}</option>
                                <option value="6 month" @if($info['campaign']['campaign_frequency'] === '6 month') selected @endif>{{ trans('salon.every_six_months') }}</option>
                                <option value="1 year" @if($info['campaign']['campaign_frequency'] === '1 year') selected @endif>{{ trans('salon.every_year') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="campaignTime">{{ trans('salon.time_to_send_emails') }}</label>
                            <select name="campaign_time" id="campaignTime" class="form-control campaign_time">
                                @foreach($time_list as $mtime=>$time)
                                    <option value="{{$mtime}}" @if($info['campaign']['campaign_time'] === $mtime) selected @endif>{{ $time }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="row vouchers-row1">
                            <div class="form-group col-xs-12">
                                <label for="includeGiftVoucher">{{ trans('salon.include_gift_voucher') }}</label>
                                <div class="radio radio-info radio-inline">
                                    <input type="radio" id="voucher1" value="1" name="include_voucher" @if($info['campaign']['gift_voucher'] != null) checked @endif>
                                    <label for="voucher1">{{ trans('salon.radio_yes') }}</label>
                                </div>
                                <div class="radio radio-inline">
                                    <input type="radio" id="voucher2" value="0" name="include_voucher" @if($info['campaign']['gift_voucher'] === null) checked @endif>
                                    <label for="voucher2">{{ trans('salon.radio_no') }}</label>
                                </div>
                            </div>
                            @if($info['campaign']['gift_voucher'] != null)
                                <select class="form-control select-voucher1 m-l" name="voucher">
                                    @foreach($vouchers as $voucher)
                                        <option value="{{ $voucher->id }}" @if($info['campaign']['gift_voucher'] === $voucher->id) checked @endif>{{ $voucher->name . ' ' . $voucher->discount . '%' }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3 class="text-muted">{{ trans('salon.marketing_campaign_criteria') }}</h3>
                        <div class="form-group">
                            <label for="clientInactive">{{ trans('salon.clients_not_been_in') }}</label>
                            {{ Form::text('clients_inactive', $info['campaign']['inactivity'], array('id' => 'clientInactive')) }}
                        </div>
                        <div class="form-group">
                            <label for="clientGender">{{ trans('salon.client_gender') }}</label>
                            <select name="clients_gender" id="clientGender" class="form-control clients-gender" required>
                                <option value="0" @if($info['campaign']['gender'] == 0) selected @endif>{{ trans('salon.all') }}</option>
                                <option value="1" @if($info['campaign']['gender'] == 1) selected @endif>{{ trans('salon.male') }}</option>
                                <option value="2" @if($info['campaign']['gender'] == 2) selected @endif>{{ trans('salon.female') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="olderThan">{{ trans('salon.older_than') }}</label>
                            {{ Form::hidden('younger_than', $info['campaign']['younger_than'], array('id' => 'youngerThan')) }}
                            {{ Form::text('client_age', $info['campaign']['older_than'], array('id' => 'clientAge')) }}
                        </div>
                        <div class="form-group">
                            <label for="withLabel">{{ trans('salon.with_label') }}</label>
                            <select name="with_label" id="withLabel" class="form-control with-label" required>
                                <option value="0" @if($info['campaign']['with_label'] == 0) selected @endif>{{ trans('salon.all') }}</option>
                                @foreach($labels as $label)
                                    <option value="{{ $label->id }}" @if($info['campaign']['with_label'] === $label->id) selected @endif>{{ $label->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="withReferral">{{ trans('salon.with_referral') }}</label>
                            <select name="with_referral" id="withReferral" class="form-control with-referral" required>
                                <option value="0" @if($info['campaign']['with_referral'] == 0) selected @endif>{{ trans('salon.all') }}</option>
                                @foreach($referrals as $referral)
                                    <option value="{{ $referral->id }}" @if($info['campaign']['with_referral'] === $referral->id) selected @endif>{{ $referral->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="withStaff">{{ trans('salon.with_staff') }}</label>
                            <select name="with_staff[]" id="withStaff" class="form-control with-staff selectpicker" multiple title="{{ trans('salon.select_options') }}">
                                <option value="0">{{ trans('salon.skip_this_criteria') }}</option>
                                @foreach($staff as $employee)
                                    <option value="{{ $employee->id }}" @foreach($info['staff'] as $staff) @if($employee->id == $staff) selected @endif @endforeach>{{ $employee->user_extras->first_name . ' ' . $employee->user_extras->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="withCategory">{{ trans('salon.with_category') }}</label>
                            <select name="with_category[]" id="withCategory" class="form-control with-category selectpicker" multiple title="{{ trans('salon.select_options') }}">
                                <option value="0">{{ trans('salon.skip_this_criteria') }}</option>
                                @foreach($location->categories as $category)
                                    <option value="{{ $category->id }}" @foreach($info['category'] as $category_check) @if($category->id == $category_check) selected @endif @endforeach>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="withService">{{ trans('salon.with_service') }}</label>
                            <select name="with_service[]" id="withService" class="form-control with-service selectpicker" multiple title="{{ trans('salon.select_options') }}">
                                <option value="0">{{ trans('salon.skip_this_criteria') }}</option>
                                @foreach($location->services as $service)
                                    <option value="{{ $service->id }}" @foreach($info['service'] as $service_check) @if($service->id == $service_check) selected @endif @endforeach>{{ $service->service_details->name . ' (' . $service->service_category->name . ')'}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="editLoyaltyPoints">{{ trans('salon.with_loyalty_points') }}</label>
                            {{ Form::text('loyalty_points', $info['campaign']['loyalty_points'], array('id' => 'loyaltyPoints', 'class' => 'form-control')) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}

        </div>
    </div>
    <script>
        var trans_age = '{{ trans('salon.age') }}';
        var trans_month = '{{ trans('salon.month_slider') }}';
    </script>
@endsection