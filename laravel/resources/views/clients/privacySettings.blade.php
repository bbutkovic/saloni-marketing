@extends('main')

@section('styles')
@endsection

@section('scripts')
    {{ HTML::script('js/clients/clients.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading pull-left">{{ trans('salon.privacy_settings') }}</h2>
        </div>
    </div>

    <div id="privacySettings">
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <h3 class="text-muted text-center">{{ trans('salon.privacy_noty_info') }}</h3>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group col-lg-6">
                                        <label class="services-label" for="allow_sms_reminders">{{ trans('salon.allow_sms_reminders') }}</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" value="1" name="allow_sms_reminders" id="smsReminders" @if(isset($user->privacy_settings) && $user->privacy_settings->sms_reminder === 1) checked @endif>
                                            <label for="allow_sms_reminders">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" value="0" name="allow_sms_reminders" @if(!isset($user->privacy_settings) || $user->privacy_settings->sms_reminder === 0) checked @endif>
                                            <label for="allow_sms_reminders">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="services-label" for="allow_sms_marketing">{{ trans('salon.allow_sms_marketing') }}</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" value="1" name="allow_sms_marketing" id="smsMarketing" @if(isset($user->privacy_settings) && $user->privacy_settings->sms_marketing === 1) checked @endif>
                                            <label for="allow_sms_marketing">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" value="0" name="allow_sms_marketing" @if(!isset($user->privacy_settings) || $user->privacy_settings->sms_marketing === 0) checked @endif>
                                            <label for="allow_sms_marketing">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label class="services-label" for="allow_email_reminders">{{ trans('salon.allow_email_reminders') }}</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" value="1" name="allow_email_reminders" id="emailReminders" @if(isset($user->privacy_settings) && $user->privacy_settings->email_reminder === 1) checked @endif>
                                            <label for="allow_email_reminders">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" value="0" name="allow_email_reminders" @if(!isset($user->privacy_settings) || $user->privacy_settings->email_reminder === 0) checked @endif>
                                            <label for="allow_email_reminders">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="services-label" for="allow_email_marketing">{{ trans('salon.allow_email_marketing') }}</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" value="1" name="allow_email_marketing" id="emailMarketing" @if(isset($user->privacy_settings) && $user->privacy_settings->email_marketing === 1) checked @endif>
                                            <label for="allow_email_marketing">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" value="0" name="allow_email_marketing" @if(!isset($user->privacy_settings) || $user->privacy_settings->email_marketing === 0) checked @endif>
                                            <label for="allow_email_marketing">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label class="services-label" for="allow_viber_reminders">{{ trans('salon.allow_viber_reminders') }}</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" value="1" name="allow_viber_reminders" id="viberReminders" @if(isset($user->privacy_settings) && $user->privacy_settings->viber_reminder === 1) checked @endif>
                                            <label for="allow_viber_reminders">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" value="0" name="allow_viber_reminders" @if(!isset($user->privacy_settings) || $user->privacy_settings->viber_reminder === 0) checked @endif>
                                            <label for="allow_viber_reminders">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="services-label" for="allow_sms_marketing">{{ trans('salon.allow_viber_marketing') }}</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" value="1" name="allow_viber_marketing" id="viberMarketing" @if(isset($user->privacy_settings) && $user->privacy_settings->viber_marketing === 1) checked @endif>
                                            <label for="allow_viber_marketing">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" value="0" name="allow_viber_marketing" @if(!isset($user->privacy_settings) || $user->privacy_settings->viber_reminder === 0) checked @endif>
                                            <label for="allow_viber_marketing">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label class="services-label" for="allow_email_reminders">{{ trans('salon.allow_facebook_reminders') }}</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" value="1" name="allow_facebook_reminders" id="facebookReminders" @if(isset($user->privacy_settings) && $user->privacy_settings->facebook_marketing === 1) checked @endif>
                                            <label for="allow_facebook_reminders">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" value="0" name="allow_facebook_reminders" @if(!isset($user->privacy_settings) || $user->privacy_settings->facebook_reminder === 0) checked @endif>
                                            <label for="allow_facebook_reminders">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label class="services-label" for="allow_facebook_marketing">{{ trans('salon.allow_facebook_marketing') }}</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" value="1" name="allow_facebook_marketing" id="facebookMarketing" @if(isset($user->privacy_settings) && $user->privacy_settings->facebook_marketing === 1) checked @endif>
                                            <label for="allow_facebook_marketing">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" value="0" name="allow_facebook_marketing" @if(!isset($user->privacy_settings) || $user->privacy_settings->facebook_reminder === 0) checked @endif>
                                            <label for="allow_facebook_marketing">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr>

                            <div class="form-group m-t">
                                <button class="btn btn-success" onclick="submitPrivacySettings()">{{ trans('salon.submit') }}</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection