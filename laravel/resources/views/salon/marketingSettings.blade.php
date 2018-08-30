@extends('main')

@section('styles')
    {{ HTML::style('css/plugins/summernote/summernote.css') }}
    {{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
@endsection

@section('scripts')
    {{ HTML::script('js/marketing/marketing.js') }}
    {{ HTML::script('js/plugins/dataTables/datatables.min.js') }}
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=944zbcd6b70j3spki3txrzecsz6n99ua5dapocup4abxci3c"></script>
    {{ HTML::script('js/plugins/jasny/jasny-bootstrap.min.js') }}
@endsection

@section('content')
<div id="location-options" class="user-settings-wrapper">
    <div class="wrapper wrapper-content">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li id="tab-1-li" class=""><a data-toggle="tab" href="#tab-1">{{ trans('salon.templates') }}</a></li>
                @if(!Auth::user()->hasRole('superadmin'))<li id="tab-2-li" class=""><a data-toggle="tab" href="#tab-2">{{ trans('salon.reminders') }}</a></li>@endif
                <li id="tab-10-li" class="active"><a data-toggle="tab" href="#tab-10">{{ trans('salon.marketing_campaigns') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane">
                    <div class="panel-body">
                        <div class="ibox ibox-content">
                            <h5 class="text-muted">{{ trans('salon.templates_tab') }}</h5>
                            <hr>
                            <button type="button" class="btn btn-default m-b" onclick="addNewTemplate(1)">{{ trans('salon.add_new_template') }}</button>
                            @if($templates != null)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover d-table" >
                                    <thead>
                                    <tr>
                                        <th class="text-center">{{ trans('salon.template_name') }}</th>
                                        <th class="text-center">{{ trans('salon.template_for') }}</th>
                                        <th class="text-center">{{ trans('salon.template_type') }}</th>
                                        <th class="text-center">{{ trans('salon.template_actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="staff-table">
                                        @foreach($templates as $marketing_template)
                                        <tr id="template{{ $marketing_template['id'] }}" class="staff-info">
                                            <td class="template-name-td text-center">{{ $marketing_template['template_name'] }}</td>
                                            <td class="template-for-td text-center">{{ $marketing_template['template_for_str'] }}</td>
                                            <td class="template-type-td text-center">{{ $marketing_template['template_type_str'] }}</td>
                                            <td class="user-options">
                                                <a href="#" onclick="editMarketingTemplate({{ $marketing_template['id'] }})">
                                                    <i class="fa fa-pencil table-profile"></i>
                                                </a>
                                                <a href="#" onclick="deleteMarketingTemplate({{ $marketing_template['id'] }})">
                                                    <i class="fa fa-trash table-delete"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                    @include('partials.marketing.addNewTemplate')
                    @include('partials.marketing.editTemplate')
                </div>

                <div id="tab-2" class="tab-pane">
                    <hr class="reminders-hr">
                    <ul class="nav nav-tabs">
                        <li id="tab-3-li" class="active"><a data-toggle="tab" href="#tab-3">{{ trans('salon.template_appointment_reminders') }}</a></li>
                        <li id="tab-4-li" class=""><a data-toggle="tab" href="#tab-4">{{ trans('salon.template_confirmation') }}</a></li>
                        <li id="tab-5-li" class=""><a data-toggle="tab" href="#tab-5">{{ trans('salon.template_reschedules') }}</a></li>
                        <li id="tab-6-li" class=""><a data-toggle="tab" href="#tab-6">{{ trans('salon.template_cancelations') }}</a></li>
                        <li id="tab-7-li" class=""><a data-toggle="tab" href="#tab-7">{{ trans('salon.template_birthday') }}</a></li>
                        <li id="tab-8-li" class=""><a data-toggle="tab" href="#tab-8">{{ trans('salon.template_loyalty_points') }}</a></li>
                        <li id="tab-9-li" class=""><a data-toggle="tab" href="#tab-9">{{ trans('salon.template_new_client') }}</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-3" class="tab-pane active">
                            <div class="panel-body">
                                <div class="ibox ibox-content">
                                    {{ Form::open(array('class' => 'reminders-form', 'data-id' => 1)) }}
                                    <small>{{ trans('salon.appointment_reminders_trans') }}</small>
                                    <div class="row m-t">
                                        <div class="col-sm-7">
                                            <hr>
                                            <div class="form-group col-md-6">
                                                <label for="noNotifications" class="text-muted">{{ trans('salon.no_notifications') }}</label>
                                                <input type="checkbox" class="checkbox-no-notifications1" name="no_notifications" onclick="disableReminders(1)" @if(isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->reminder_status === 0) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="emailNotifications" class="text-muted">Email</label>
                                                <input type="checkbox" class="checkbox-email-notifications1 checkbox-reminder1" name="email_notify" @if(isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->email_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="smsNotifications" class="text-muted">SMS</label>
                                                <input type="checkbox" class="checkbox-sms-notifications1 checkbox-reminder1" name="sms_notify" @if(isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->sms_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="viberNotifications">Viber</label>
                                                <input type="checkbox" class="checkbox-viber-notifications1 checkbox-reminder1" name="viber_notify" @if(isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->viber_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="messengerNotifications">Facebook Messenger</label>
                                                <input type="checkbox" class="checkbox-messenger-notifications1 checkbox-reminder1" name="messenger_notify" @if(isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->messenger_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="pushNotifications">Push Notification</label>
                                                <input type="checkbox" class="checkbox-push-notifications1 checkbox-reminder1" name="push_notify" @if(isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->push_template != null) checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4 template-selection-wrap1 @if(!isset($reminders[0]) || (isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->email_template === null)) hidden @endif">
                                            <div class="email-template1">
                                                <h5 class="text-muted">{{ trans('salon.select_email_template') }}</h5>
                                                <select class="email-template-select1 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 1)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[0]) || (isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->email_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap1 @if(!isset($reminders[0]) || (isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->sms_template === null)) hidden @endif">
                                            <div class="sms-template1">
                                                <h5 class="text-muted">{{ trans('salon.select_sms_template') }}</h5>
                                                <select class="email-template-select1 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 2)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[0]) || (isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->sms_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap1 @if(!isset($reminders[0]) || (isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->viber_template === null)) hidden @endif">
                                            <div class="viber-template1">
                                                <h5 class="text-muted">{{ trans('salon.select_viber_template') }}</h5>
                                                <select class="email-template-select1 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 3)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[0]) || (isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->viber_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap1 @if(!isset($reminders[0]) || (isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->messenger_template === null)) hidden @endif">
                                            <div class="messenger-template1">
                                                <h5 class="text-muted">{{ trans('salon.select_messenger_template') }}</h5>
                                                <select class="email-template-select1 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 4)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[0]) || (isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->messenger_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap1 @if(!isset($reminders[0]) || (isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->push_template === null)) hidden @endif">
                                            <div class="push-template1">
                                                <h5 class="text-muted">{{ trans('salon.select_push_template') }}</h5>
                                                <select class="email-template-select1 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 5)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[0]) || (isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->push_tempalte === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row vouchers-row1">
                                        <div class="form-group col-xs-12">
                                            <label for="includeGiftVoucher">{{ trans('salon.include_gift_voucher') }}</label>
                                            <div class="radio radio-info radio-inline">
                                                <input type="radio" id="voucher1" value="1" name="include_voucher" @if(isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->gift_voucher != null)) checked @endif>
                                                <label for="voucher1">{{ trans('salon.radio_yes') }}</label>
                                            </div>
                                            <div class="radio radio-inline">
                                                <input type="radio" id="voucher2" value="0" name="include_voucher" @if(!isset($reminders[0]) || (isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->gift_voucher === null)) checked @endif>
                                                <label for="voucher2">{{ trans('salon.radio_no') }}</label>
                                            </div>
                                        </div>
                                        @if(isset($reminders[0]) && $reminders[0]->reminder_type === 1 && $reminders[0]->gift_voucher != null)
                                            <select class="form-control select-voucher1 m-l">
                                                @foreach($vouchers as $voucher)
                                                    <option value="{{ $voucher->id }}" @if(isset($reminders[0]) && $reminders[0]->gift_voucher === $voucher->id) selected @endif>{{ $voucher->name . ' ' . $voucher->discount . '%' }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="row m-t">
                                        <div class="form-group col-xs-12">
                                            <label for="sendBefore1">{{ trans('salon.send_before') }}</label>
                                            <select name="send_before" id="sendBefore1" class="form-control send-before-select">
                                                <option value="0" default disabled>{{ trans('salon.select_time') }}</option>
                                                <option value="01:00" @if(isset($reminders[0]) && $reminders[0]->send_before === '01:00:00') selected @endif>{{ trans('salon.one_hour') }}</option>
                                                <option value="02:00" @if(isset($reminders[0]) && $reminders[0]->send_before === '02:00:00') selected @endif>{{ trans('salon.two_hours') }}</option>
                                                <option value="06:00" @if(isset($reminders[0]) && $reminders[0]->send_before === '06:00:00') selected @endif>{{ trans('salon.six_hours') }}</option>
                                                <option value="24:00" @if(isset($reminders[0]) && $reminders[0]->send_before === '24:00:00') selected @endif>{{ trans('salon.one_day') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <button type="button" class="btn btn-success m-l" onclick="submitReminderSettings(1)">{{ trans('salon.submit') }}</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>

                        <div id="tab-4" class="tab-pane">
                            <div class="panel-body">
                                <div class="ibox ibox-content">
                                    {{ Form::open(array('class' => 'reminders-form', 'data-id' => 2)) }}
                                    <small>{{ trans('salon.appointment_confirmation_desc') }}</small>
                                    <hr>
                                    <div class="row m-t">
                                        <div class="col-sm-7">
                                            <div class="form-group col-md-6">
                                                <label for="noNotifications" class="text-muted">{{ trans('salon.no_notifications') }}</label>
                                                <input type="checkbox" class="checkbox-no-notifications2" name="no_notifications" onclick="disableReminders(2)" @if(isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->reminder_status === 0) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="emailNotifications" class="text-muted">Email</label>
                                                <input type="checkbox" class="checkbox-email-notifications2 checkbox-reminder2" name="email_notify" @if(isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->email_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="smsNotifications" class="text-muted">SMS</label>
                                                <input type="checkbox" class="checkbox-sms-notifications2 checkbox-reminder2" name="sms_notify" @if(isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->sms_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="viberNotifications">Viber</label>
                                                <input type="checkbox" class="checkbox-viber-notifications2 checkbox-reminder2" name="viber_notify" @if(isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->viber_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="messengerNotifications">Facebook Messenger</label>
                                                <input type="checkbox" class="checkbox-messenger-notifications2 checkbox-reminder2" name="messenger_notify" @if(isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->messenger_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="pushNotifications">Push Notification</label>
                                                <input type="checkbox" class="checkbox-push-notifications2 checkbox-reminder2" name="push_notify" @if(isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->push_template != null) checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4 template-selection-wrap2 @if(!isset($reminders[1]) || (isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->email_template === null)) hidden @endif">
                                            <div class="email-template2">
                                                <h5 class="text-muted">{{ trans('salon.select_email_template') }}</h5>
                                                <select class="email-template-select2 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 1)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[1]) || (isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->email_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap2 @if(!isset($reminders[1]) || (isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->sms_template === null)) hidden @endif">
                                            <div class="sms-template2">
                                                <h5 class="text-muted">{{ trans('salon.select_sms_template') }}</h5>
                                                <select class="email-template-select2 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 2)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[1]) || (isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->sms_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap2 @if(!isset($reminders[1]) || (isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->viber_template === null)) hidden @endif">
                                            <div class="viber-template2">
                                                <h5 class="text-muted">{{ trans('salon.select_viber_template') }}</h5>
                                                <select class="email-template-select2 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 3)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[1]) || (isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->viber_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap2 @if(!isset($reminders[1]) || (isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->messenger_template === null)) hidden @endif">
                                            <div class="messenger-template2">
                                                <h5 class="text-muted">{{ trans('salon.select_messenger_template') }}</h5>
                                                <select class="email-template-select2 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 4)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[1]) || (isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->messenger_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap2 @if(!isset($reminders[1]) || (isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->push_template === null)) hidden @endif">
                                            <div class="push-template2">
                                                <h5 class="text-muted">{{ trans('salon.select_push_template') }}</h5>
                                                <select class="email-template-select2 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 5)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[1]) || (isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->push_tempalte === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row vouchers-row2">
                                        <div class="form-group col-xs-12">
                                            <label for="includeGiftVoucher">{{ trans('salon.include_gift_voucher') }}</label>
                                            <div class="radio radio-info radio-inline">
                                                <input type="radio" id="voucher1" value="1" name="include_voucher" @if(isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->gift_voucher != null)) checked @endif>
                                                <label for="voucher1">{{ trans('salon.radio_yes') }}</label>
                                            </div>
                                            <div class="radio radio-inline">
                                                <input type="radio" id="voucher2" value="0" name="include_voucher" @if(!isset($reminders[1]) || (isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->gift_voucher === null)) checked @endif>
                                                <label for="voucher2">{{ trans('salon.radio_no') }}</label>
                                            </div>
                                        </div>
                                        @if(isset($reminders[1]) && $reminders[1]->reminder_type === 2 && $reminders[1]->gift_voucher != null)
                                            <select class="form-control select-voucher2 m-l">
                                                @foreach($vouchers as $voucher)
                                                    <option value="{{ $voucher->id }}" @if(isset($reminders[1]) && $reminders[1]->gift_voucher === $voucher->id) selected @endif>{{ $voucher->name . ' ' . $voucher->discount . '%' }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <button type="button" class="btn btn-success m-l" onclick="submitReminderSettings(2)">{{ trans('salon.submit') }}</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>

                        <div id="tab-5" class="tab-pane">
                            <div class="panel-body">
                                <div class="ibox ibox-content">
                                    {{ Form::open(array('class' => 'reminders-form', 'data-id' => 3)) }}
                                    <small>{{ trans('salon.appointment_reschedules_desc') }}</small>
                                    <hr>
                                    <div class="row m-t">
                                        <div class="col-sm-7">
                                            <div class="form-group col-md-6">
                                                <label for="noNotifications" class="text-muted">{{ trans('salon.no_notifications') }}</label>
                                                <input type="checkbox" class="checkbox-no-notifications3" name="no_notifications" onclick="disableReminders(3)" @if(isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->reminder_status === 0) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="emailNotifications" class="text-muted">Email</label>
                                                <input type="checkbox" class="checkbox-email-notifications3 checkbox-reminder3" name="email_notify" @if(isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->email_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="smsNotifications" class="text-muted">SMS</label>
                                                <input type="checkbox" class="checkbox-sms-notifications3 checkbox-reminder3" name="sms_notify" @if(isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->sms_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="viberNotifications">Viber</label>
                                                <input type="checkbox" class="checkbox-viber-notifications3 checkbox-reminder3" name="viber_notify" @if(isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->viber_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="messengerNotifications">Facebook Messenger</label>
                                                <input type="checkbox" class="checkbox-messenger-notifications3 checkbox-reminder3" name="messenger_notify" @if(isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->messenger_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="pushNotifications">Push Notification</label>
                                                <input type="checkbox" class="checkbox-push-notifications3 checkbox-reminder3" name="push_notify" @if(isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->push_template != null) checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4 template-selection-wrap3 @if(!isset($reminders[2]) || (isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->email_template === null)) hidden @endif">
                                            <div class="email-template3">
                                                <h5 class="text-muted">{{ trans('salon.select_email_template') }}</h5>
                                                <select class="email-template-select3 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 1)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[2]) || (isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->email_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap3 @if(!isset($reminders[2]) || (isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->sms_template === null)) hidden @endif">
                                            <div class="sms-template3">
                                                <h5 class="text-muted">{{ trans('salon.select_sms_template') }}</h5>
                                                <select class="email-template-select3 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 2)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[2]) || (isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->sms_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap3 @if(!isset($reminders[2]) || (isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->viber_template === null)) hidden @endif">
                                            <div class="viber-template3">
                                                <h5 class="text-muted">{{ trans('salon.select_viber_template') }}</h5>
                                                <select class="email-template-select3 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 3)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[2]) || (isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->viber_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap3 @if(!isset($reminders[2]) || (isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->messenger_template === null)) hidden @endif">
                                            <div class="messenger-template3">
                                                <h5 class="text-muted">{{ trans('salon.select_messenger_template') }}</h5>
                                                <select class="email-template-select3 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 4)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[2]) || (isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->messenger_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap3 @if(!isset($reminders[2]) || (isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->push_template === null)) hidden @endif">
                                            <div class="push-template3">
                                                <h5 class="text-muted">{{ trans('salon.select_push_template') }}</h5>
                                                <select class="email-template-select3 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 5)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[2]) || (isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->push_tempalte === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row vouchers-row3">
                                        <div class="form-group col-xs-12">
                                            <label for="includeGiftVoucher">{{ trans('salon.include_gift_voucher') }}</label>
                                            <div class="radio radio-info radio-inline">
                                                <input type="radio" id="voucher1" value="1" name="include_voucher" @if(isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->gift_voucher != null)) checked @endif>
                                                <label for="voucher1">{{ trans('salon.radio_yes') }}</label>
                                            </div>
                                            <div class="radio radio-inline">
                                                <input type="radio" id="voucher2" value="0" name="include_voucher" @if(!isset($reminders[2]) || (isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->gift_voucher === null)) checked @endif>
                                                <label for="voucher2">{{ trans('salon.radio_no') }}</label>
                                            </div>
                                        </div>
                                        @if(isset($reminders[2]) && $reminders[2]->reminder_type === 3 && $reminders[2]->gift_voucher != null)
                                            <select class="form-control select-voucher3 m-l">
                                                @foreach($vouchers as $voucher)
                                                    <option value="{{ $voucher->id }}" @if(isset($reminders[2]) && $reminders[2]->gift_voucher === $voucher->id) selected @endif>{{ $voucher->name . ' ' . $voucher->discount . '%' }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <button type="button" class="btn btn-success m-l" onclick="submitReminderSettings(3)">{{ trans('salon.submit') }}</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>

                        <div id="tab-6" class="tab-pane">
                            <div class="panel-body">
                                <div class="ibox ibox-content">
                                    {{ Form::open(array('class' => 'reminders-form', 'data-id' => 4)) }}
                                    <small>{{ trans('salon.appointment_cancelations_desc') }}</small>
                                    <hr>
                                    <div class="row m-t">
                                        <div class="col-sm-7">
                                            <div class="form-group col-md-6">
                                                <label for="noNotifications" class="text-muted">{{ trans('salon.no_notifications') }}</label>
                                                <input type="checkbox" class="checkbox-no-notifications4" name="no_notifications" onclick="disableReminders(4)" @if(isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->reminder_status === 0) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="emailNotifications" class="text-muted">Email</label>
                                                <input type="checkbox" class="checkbox-email-notifications4 checkbox-reminder4" name="email_notify" @if(isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->email_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="smsNotifications" class="text-muted">SMS</label>
                                                <input type="checkbox" class="checkbox-sms-notifications4 checkbox-reminder4" name="sms_notify" @if(isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->sms_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="viberNotifications">Viber</label>
                                                <input type="checkbox" class="checkbox-viber-notifications4 checkbox-reminder4" name="viber_notify" @if(isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->viber_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="messengerNotifications">Facebook Messenger</label>
                                                <input type="checkbox" class="checkbox-messenger-notifications4 checkbox-reminder4" name="messenger_notify" @if(isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->messenger_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="pushNotifications">Push Notification</label>
                                                <input type="checkbox" class="checkbox-push-notifications4 checkbox-reminder4" name="push_notify" @if(isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->push_template != null) checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4 template-selection-wrap4 @if(!isset($reminders[3]) || (isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->email_template === null)) hidden @endif">
                                            <div class="email-template4">
                                                <h5 class="text-muted">{{ trans('salon.select_email_template') }}</h5>
                                                <select class="email-template-select4 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 1)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[3]) || (isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->email_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap4 @if(!isset($reminders[3]) || (isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->sms_template === null)) hidden @endif">
                                            <div class="sms-template4">
                                                <h5 class="text-muted">{{ trans('salon.select_sms_template') }}</h5>
                                                <select class="email-template-select4 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 2)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[3]) || (isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->sms_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap4 @if(!isset($reminders[3]) || (isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->viber_template === null)) hidden @endif">
                                            <div class="viber-template4">
                                                <h5 class="text-muted">{{ trans('salon.select_viber_template') }}</h5>
                                                <select class="email-template-select4 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 3)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[3]) || (isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->viber_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap4 @if(!isset($reminders[3]) || (isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->messenger_template === null)) hidden @endif">
                                            <div class="messenger-template4">
                                                <h5 class="text-muted">{{ trans('salon.select_messenger_template') }}</h5>
                                                <select class="email-template-select4 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 4)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[3]) || (isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->messenger_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap4 @if(!isset($reminders[3]) || (isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->push_template === null)) hidden @endif">
                                            <div class="push-template4">
                                                <h5 class="text-muted">{{ trans('salon.select_push_template') }}</h5>
                                                <select class="email-template-select4 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 5)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[3]) || (isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->push_tempalte === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row vouchers-row4">
                                        <div class="form-group col-xs-12">
                                            <label for="includeGiftVoucher">{{ trans('salon.include_gift_voucher') }}</label>
                                            <div class="radio radio-info radio-inline">
                                                <input type="radio" id="voucher1" value="1" name="include_voucher" @if(isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->gift_voucher != null)) checked @endif>
                                                <label for="voucher1">{{ trans('salon.radio_yes') }}</label>
                                            </div>
                                            <div class="radio radio-inline">
                                                <input type="radio" id="voucher2" value="0" name="include_voucher" @if(!isset($reminders[3]) || (isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->gift_voucher === null)) checked @endif>
                                                <label for="voucher2">{{ trans('salon.radio_no') }}</label>
                                            </div>
                                        </div>
                                        @if(isset($reminders[3]) && $reminders[3]->reminder_type === 4 && $reminders[3]->gift_voucher != null)
                                            <select class="form-control select-voucher4 m-l">
                                                @foreach($vouchers as $voucher)
                                                    <option value="{{ $voucher->id }}" @if(isset($reminders[3]) && $reminders[3]->gift_voucher === $voucher->id) selected @endif>{{ $voucher->name . ' ' . $voucher->discount . '%' }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <button type="button" class="btn btn-success m-l" onclick="submitReminderSettings(4)">{{ trans('salon.submit') }}</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>

                        <div id="tab-7" class="tab-pane">
                            <div class="panel-body">
                                <div class="ibox ibox-content">
                                    {{ Form::open(array('class' => 'reminders-form', 'data-id' => 5)) }}
                                    <small>{{ trans('salon.birthday_desc') }}</small>
                                    <hr>
                                    <div class="row m-t">
                                        <div class="col-sm-7">
                                            <div class="form-group col-md-6">
                                                <label for="noNotifications" class="text-muted">{{ trans('salon.no_notifications') }}</label>
                                                <input type="checkbox" class="checkbox-no-notifications5" name="no_notifications" onclick="disableReminders(5)" @if(isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->reminder_status === 0) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="emailNotifications" class="text-muted">Email</label>
                                                <input type="checkbox" class="checkbox-email-notifications5 checkbox-reminder5" name="email_notify" @if(isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->email_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="smsNotifications" class="text-muted">SMS</label>
                                                <input type="checkbox" class="checkbox-sms-notifications5 checkbox-reminder5" name="sms_notify" @if(isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->sms_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="viberNotifications">Viber</label>
                                                <input type="checkbox" class="checkbox-viber-notifications5 checkbox-reminder5" name="viber_notify" @if(isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->viber_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="messengerNotifications">Facebook Messenger</label>
                                                <input type="checkbox" class="checkbox-messenger-notifications5 checkbox-reminder5" name="messenger_notify" @if(isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->messenger_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="pushNotifications">Push Notification</label>
                                                <input type="checkbox" class="checkbox-push-notifications5 checkbox-reminder5" name="push_notify" @if(isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->push_template != null) checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4 template-selection-wrap5 @if(!isset($reminders[4]) || (isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->email_template === null)) hidden @endif">
                                            <div class="email-template5">
                                                <h5 class="text-muted">{{ trans('salon.select_email_template') }}</h5>
                                                <select class="email-template-select5 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 1)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[4]) || (isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->email_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap5 @if(!isset($reminders[4]) || (isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->sms_template === null)) hidden @endif">
                                            <div class="sms-template5">
                                                <h5 class="text-muted">{{ trans('salon.select_sms_template') }}</h5>
                                                <select class="email-template-select5 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 2)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[4]) || (isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->sms_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap5 @if(!isset($reminders[4]) || (isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->viber_template === null)) hidden @endif">
                                            <div class="viber-template5">
                                                <h5 class="text-muted">{{ trans('salon.select_viber_template') }}</h5>
                                                <select class="email-template-select5 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 3)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[4]) || (isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->viber_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap5 @if(!isset($reminders[4]) || (isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->messenger_template === null)) hidden @endif">
                                            <div class="messenger-template5">
                                                <h5 class="text-muted">{{ trans('salon.select_messenger_template') }}</h5>
                                                <select class="email-template-select5 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 4)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[4]) || (isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->messenger_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap5 @if(!isset($reminders[4]) || (isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->push_template === null)) hidden @endif">
                                            <div class="push-template5">
                                                <h5 class="text-muted">{{ trans('salon.select_push_template') }}</h5>
                                                <select class="email-template-select5 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 5)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[4]) || (isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->push_tempalte === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row vouchers-row5">
                                        <div class="form-group col-xs-12">
                                            <label for="includeGiftVoucher">{{ trans('salon.include_gift_voucher') }}</label>
                                            <div class="radio radio-info radio-inline">
                                                <input type="radio" id="voucher1" value="1" name="include_voucher" @if(isset($reminders[4]) && $reminders[4]->reminder_type === 3 && $reminders[4]->gift_voucher != null)) checked @endif>
                                                <label for="voucher1">{{ trans('salon.radio_yes') }}</label>
                                            </div>
                                            <div class="radio radio-inline">
                                                <input type="radio" id="voucher2" value="0" name="include_voucher" @if(!isset($reminders[4]) || (isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->gift_voucher === null)) checked @endif>
                                                <label for="voucher2">{{ trans('salon.radio_no') }}</label>
                                            </div>
                                        </div>
                                        @if(isset($reminders[4]) && $reminders[4]->reminder_type === 5 && $reminders[4]->gift_voucher != null)
                                            <select class="form-control select-voucher5 m-l">
                                                @foreach($vouchers as $voucher)
                                                    <option value="{{ $voucher->id }}" @if(isset($reminders[4]) && $reminders[4]->gift_voucher === $voucher->id) selected @endif>{{ $voucher->name . ' ' . $voucher->discount . '%' }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <button type="button" class="btn btn-success m-l" onclick="submitReminderSettings(5)">{{ trans('salon.submit') }}</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>

                        <div id="tab-8" class="tab-pane">
                            <div class="panel-body">
                                <div class="ibox ibox-content">
                                    {{ Form::open(array('class' => 'reminders-form', 'data-id' => 6)) }}
                                    <small>{{ trans('salon.appointment_loyalty_desc') }}</small>
                                    <hr>
                                    <div class="row m-t">
                                        <div class="col-sm-7">
                                            <div class="form-group col-md-6">
                                                <label for="noNotifications" class="text-muted">{{ trans('salon.no_notifications') }}</label>
                                                <input type="checkbox" class="checkbox-no-notifications6" name="no_notifications" onclick="disableReminders(6)" @if(isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->reminder_status === 0) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="emailNotifications" class="text-muted">Email</label>
                                                <input type="checkbox" class="checkbox-email-notifications6 checkbox-reminder6" name="email_notify" @if(isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->email_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="smsNotifications" class="text-muted">SMS</label>
                                                <input type="checkbox" class="checkbox-sms-notifications6 checkbox-reminder6" name="sms_notify" @if(isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->sms_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="viberNotifications">Viber</label>
                                                <input type="checkbox" class="checkbox-viber-notifications6 checkbox-reminder6" name="viber_notify" @if(isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->viber_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="messengerNotifications">Facebook Messenger</label>
                                                <input type="checkbox" class="checkbox-messenger-notifications6 checkbox-reminder6" name="messenger_notify" @if(isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->messenger_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="pushNotifications">Push Notification</label>
                                                <input type="checkbox" class="checkbox-push-notifications6 checkbox-reminder6" name="push_notify" @if(isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->push_template != null) checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4 template-selection-wrap6 @if(!isset($reminders[5]) || (isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->email_template === null)) hidden @endif">
                                            <div class="email-template6">
                                                <h5 class="text-muted">{{ trans('salon.select_email_template') }}</h5>
                                                <select class="email-template-select6 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 1)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[5]) || (isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->email_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap6 @if(!isset($reminders[5]) || (isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->sms_template === null)) hidden @endif">
                                            <div class="sms-template6">
                                                <h5 class="text-muted">{{ trans('salon.select_sms_template') }}</h5>
                                                <select class="email-template-select6 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 2)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[5]) || (isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->sms_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap6 @if(!isset($reminders[5]) || (isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->viber_template === null)) hidden @endif">
                                            <div class="viber-template6">
                                                <h5 class="text-muted">{{ trans('salon.select_viber_template') }}</h5>
                                                <select class="email-template-select6 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 3)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[5]) || (isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->viber_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap6 @if(!isset($reminders[5]) || (isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->messenger_template === null)) hidden @endif">
                                            <div class="messenger-template6">
                                                <h5 class="text-muted">{{ trans('salon.select_messenger_template') }}</h5>
                                                <select class="email-template-select6 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 4)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[5]) || (isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->messenger_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap6 @if(!isset($reminders[5]) || (isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->push_template === null)) hidden @endif">
                                            <div class="push-template6">
                                                <h5 class="text-muted">{{ trans('salon.select_push_template') }}</h5>
                                                <select class="email-template-select6 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 5)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[5]) || (isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->push_tempalte === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row vouchers-row6">
                                        <div class="form-group col-xs-12">
                                            <label for="includeGiftVoucher">{{ trans('salon.include_gift_voucher') }}</label>
                                            <div class="radio radio-info radio-inline">
                                                <input type="radio" id="voucher1" value="1" name="include_voucher" @if(isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->gift_voucher != null)) checked @endif>
                                                <label for="voucher1">{{ trans('salon.radio_yes') }}</label>
                                            </div>
                                            <div class="radio radio-inline">
                                                <input type="radio" id="voucher2" value="0" name="include_voucher" @if(!isset($reminders[5]) || (isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->gift_voucher === null)) checked @endif>
                                                <label for="voucher2">{{ trans('salon.radio_no') }}</label>
                                            </div>
                                        </div>
                                        @if(isset($reminders[5]) && $reminders[5]->reminder_type === 6 && $reminders[5]->gift_voucher != null)
                                            <select class="form-control select-voucher6 m-l">
                                                @foreach($vouchers as $voucher)
                                                    <option value="{{ $voucher->id }}" @if(isset($reminders[5]) && $reminders[5]->gift_voucher === $voucher->id) selected @endif>{{ $voucher->name . ' ' . $voucher->discount . '%' }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <button type="button" class="btn btn-success m-l" onclick="submitReminderSettings(6)">{{ trans('salon.submit') }}</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>

                        <div id="tab-9" class="tab-pane">
                            <div class="panel-body">
                                <div class="ibox ibox-content">
                                    {{ Form::open(array('class' => 'reminders-form', 'data-id' => 7)) }}
                                    <small>{{ trans('salon.new_clients_desc') }}</small>
                                    <hr>
                                    <div class="row m-t">
                                        <div class="col-sm-7">
                                            <div class="form-group col-md-6">
                                                <label for="noNotifications" class="text-muted">{{ trans('salon.no_notifications') }}</label>
                                                <input type="checkbox" class="checkbox-no-notifications7" name="no_notifications" onclick="disableReminders(7)" @if(isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->reminder_status === 0) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="emailNotifications" class="text-muted">Email</label>
                                                <input type="checkbox" class="checkbox-email-notifications7 checkbox-reminder7" name="email_notify" @if(isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->email_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="smsNotifications" class="text-muted">SMS</label>
                                                <input type="checkbox" class="checkbox-sms-notifications7 checkbox-reminder7" name="sms_notify" @if(isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->sms_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="viberNotifications">Viber</label>
                                                <input type="checkbox" class="checkbox-viber-notifications7 checkbox-reminder7" name="viber_notify" @if(isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->viber_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="messengerNotifications">Facebook Messenger</label>
                                                <input type="checkbox" class="checkbox-messenger-notifications7 checkbox-reminder7" name="messenger_notify" @if(isset($reminders[6]) && $reminders[0]->reminder_type === 7 && $reminders[6]->messenger_template != null) checked @endif>
                                            </div>
                                            <div class="form-group col-md-6" class="text-muted">
                                                <label for="pushNotifications">Push Notification</label>
                                                <input type="checkbox" class="checkbox-push-notifications7 checkbox-reminder7" name="push_notify" @if(isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->push_template != null) checked @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-4 template-selection-wrap7 @if(!isset($reminders[6]) || (isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->email_template === null)) hidden @endif">
                                            <div class="email-template7">
                                                <h5 class="text-muted">{{ trans('salon.select_email_template') }}</h5>
                                                <select class="email-template-select7 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 1)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[6]) || (isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->email_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap7 @if(!isset($reminders[6]) || (isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->sms_template === null)) hidden @endif">
                                            <div class="sms-template7">
                                                <h5 class="text-muted">{{ trans('salon.select_sms_template') }}</h5>
                                                <select class="email-template-select7 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 2)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[6]) || (isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->sms_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap7 @if(!isset($reminders[6]) || (isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->viber_template === null)) hidden @endif">
                                            <div class="viber-template7">
                                                <h5 class="text-muted">{{ trans('salon.select_viber_template') }}</h5>
                                                <select class="email-template-select7 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 3)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[6]) || (isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->viber_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap7 @if(!isset($reminders[6]) || (isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->messenger_template === null)) hidden @endif">
                                            <div class="messenger-template7">
                                                <h5 class="text-muted">{{ trans('salon.select_messenger_template') }}</h5>
                                                <select class="email-template-select7 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 4)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[6]) || (isset($reminders[6]) && $reminders[66]->reminder_type === 7 && $reminders[6]->messenger_template === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 template-selection-wrap7 @if(!isset($reminders[6]) || (isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->push_template === null)) hidden @endif">
                                            <div class="push-template7">
                                                <h5 class="text-muted">{{ trans('salon.select_push_template') }}</h5>
                                                <select class="email-template-select7 form-control">
                                                    <option value="0" default selected>{{ trans('salon.select_template') }}</option>
                                                    @foreach($templates as $template)
                                                        @if($template['template_for'] === 5)
                                                            <option value="{{ $template['id'] }}" @if(!isset($reminders[6]) || (isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->push_tempalte === $template['id'])) selected @endif>{{ $template['template_name'] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row vouchers-row7">
                                        <div class="form-group col-xs-12">
                                            <label for="includeGiftVoucher">{{ trans('salon.include_gift_voucher') }}</label>
                                            <div class="radio radio-info radio-inline">
                                                <input type="radio" id="voucher1" value="1" name="include_voucher" @if(isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->gift_voucher != null)) checked @endif>
                                                <label for="voucher1">{{ trans('salon.radio_yes') }}</label>
                                            </div>
                                            <div class="radio radio-inline">
                                                <input type="radio" id="voucher2" value="0" name="include_voucher" @if(!isset($reminders[6]) || (isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->gift_voucher === null)) checked @endif>
                                                <label for="voucher2">{{ trans('salon.radio_no') }}</label>
                                            </div>
                                        </div>
                                        @if(isset($reminders[6]) && $reminders[6]->reminder_type === 7 && $reminders[6]->gift_voucher != null)
                                            <select class="form-control select-voucher7 m-l">
                                                @foreach($vouchers as $voucher)
                                                    <option value="{{ $voucher->id }}" @if(isset($reminders[6]) && $reminders[6]->gift_voucher === $voucher->id) selected @endif>{{ $voucher->name . ' ' . $voucher->discount . '%' }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                    <div class="row">
                                        <button type="button" class="btn btn-success m-l" onclick="submitReminderSettings(7)">{{ trans('salon.submit') }}</button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="tab-10" class="tab-pane active">
                    <div class="panel-body">
                        <div class="ibox ibox-content">
                            <a href="{{ route('campaignCreation') }}"><button type="button" class="btn btn-default m-b">{{ trans('salon.add_new_campaign') }}</button></a>
                            @if(isset($location->location_campaigns) && $location->location_campaigns != null)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover d-table">
                                        <thead>
                                        <tr>
                                            <th class="text-center">{{ trans('salon.campaign_name') }}</th>
                                            <th class="text-center">{{ trans('salon.created_at') }}</th>
                                            <th class="text-center">{{ trans('salon.template_actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody class="staff-table">
                                        @foreach($location->location_campaigns as $campaign)
                                            <tr id="campaign{{ $campaign['id'] }}" class="staff-info">
                                                <td class="template-name-td text-center">{{ $campaign['name'] }}</td>
                                                <td class="template-type-td text-center">{{ date('d M Y', strtotime($campaign['created_at'])) }}</td>
                                                <td class="user-options">
                                                    <a href="#" onclick="sendCampaign({{ $campaign['id'] }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.send_emails') }}">
                                                        <i class="fa fa-paper-plane table-profile"></i>
                                                    </a>
                                                    <a href="{{ route('getCampaignEdit', $campaign['id']) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.edit_campaign') }}">
                                                        <i class="fa fa-pencil table-profile"></i>
                                                    </a>
                                                    <a href="#" onclick="deleteCampaign({{ $campaign['id'] }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.delete_campaign') }}">
                                                        <i class="fa fa-trash table-delete"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            @include('partials.marketing.addNewCampaign')
                            @include('partials.marketing.editCampaign')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var swal_alert = '{{ trans('salon.are_you_sure') }}';
    var trans_age = '{{ trans('salon.age') }}';
    var trans_month = '{{ trans('salon.month_slider') }}';
</script>

@endsection