<div class="modal fade" id="addNewCampaignModal" tabindex="-1" role="dialog" aria-labelledby="addNewCampaignModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.add_new_campaign') }}</h4>
            </div>
            {{ Form::open(array('route' => 'addNewCampaign', 'id' => 'addNewCampaignForm')) }}
            <div class="modal-body">
                <div class="form-group">
                    <label for="campaignName">{{ trans('salon.campaign_name') }}</label>
                    {{ Form::text('campaign_name', null, array('id' => 'campaignName', 'class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    <label for="campaignSubject">{{ trans('salon.campaign_subject') }}</label>
                    {{ Form::text('campaign_subject', null, array('id' => 'campaignSubject', 'class' => 'form-control')) }}
                </div>
                <hr>
                <h3 class="text-muted">{{ trans('salon.marketing_campaign_criteria') }}</h3>
                <div class="form-group">
                    <label for="clientInactive">{{ trans('salon.clients_not_been_in') }}</label>
                    <select name="clients_inactive" id="clientInactive" class="form-control clients-inactive">
                        <option value="0">{{ trans('salon.skip_this_criteria') }}</option>
                        <option value="2">{{ trans('salon.two_months') }}</option>
                        <option value="3">{{ trans('salon.three_months') }}</option>
                        <option value="4">{{ trans('salon.four_months') }}</option>
                        <option value="5">{{ trans('salon.five_months') }}</option>
                        <option value="6">{{ trans('salon.six_months') }}</option>
                        <option value="7">{{ trans('salon.seven_months') }}</option>
                        <option value="8">{{ trans('salon.eight_months') }}</option>
                        <option value="9">{{ trans('salon.nine_months') }}</option>
                        <option value="10">{{ trans('salon.ten_months') }}</option>
                        <option value="11">{{ trans('salon.eleven_months') }}</option>
                        <option value="12">{{ trans('salon.one_year') }}</option>
                        <option value="24">{{ trans('salon.two_years') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="clientGender">{{ trans('salon.client_gender') }}</label>
                    <select name="clients_gender" id="clientGender" class="form-control clients-gender">
                        <option value="0" default selected>{{ trans('salon.all') }}</option>
                        <option value="1">{{ trans('salon.male') }}</option>
                        <option value="2">{{ trans('salon.female') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="olderThan">{{ trans('salon.older_than') }}</label>
                    {{ Form::text('older_than', null, array('id' => 'olderThan', 'class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    <label for="withLabel">{{ trans('salon.with_label') }}</label>
                    <select name="with_label" id="withLabel" class="form-control with-label">
                        <option value="0" default selected>{{ trans('salon.all') }}</option>
                        @foreach($labels as $label)
                            <option value="{{ $label->id }}">{{ $label->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="withReferral">{{ trans('salon.with_referral') }}</label>
                    <select name="with_referral" id="withReferral" class="form-control with-referral">
                        <option value="0" default selected>{{ trans('salon.all') }}</option>
                        @foreach($referrals as $referral)
                            <option value="{{ $referral->id }}">{{ $referral->name }}</option>
                        @endforeach
                    </select>
                </div>
                <hr>
                <div class="form-group">
                    <label for="tinymce">{{ trans('salon.template_content') }}</label>
                    <div class="template-content">
                        <textarea id="tinymce" class="tinymce-textarea text-campaign" name="editordata"></textarea>
                        <input name="image" type="file" id="upload" class="hidden appended-textarea" onchange="">
                    </div>
                </div>
                <div class="template-content-fields">
                    <small class="text-muted">{{ trans('salon.template_fields_desc') }}</small>
                    <div class="fields-wrap">
                        <a href="#" class="template-field appended-field" id="templateField1" onclick="addFieldToTemplateContent(1)">ClientFirstName</a>
                        <a href="#" class="template-field appended-field" id="templateField2" onclick="addFieldToTemplateContent(2)">ClientLastName</a>
                        <a href="#" class="template-field appended-field" id="templateField3" onclick="addFieldToTemplateContent(3)">CurrentDate</a>
                        <a href="#" class="template-field appended-field" id="templateField4" onclick="addFieldToTemplateContent(4)">BusinessName</a>
                        <a href="#" class="template-field appended-field" id="templateField5" onclick="addFieldToTemplateContent(5)">BusinessPhone</a>
                        <a href="#" class="template-field appended-field" id="templateField6" onclick="addFieldToTemplateContent(6)">BusinessAddress</a>
                        <a href="#" class="template-field appended-field" id="templateField7" onclick="addFieldToTemplateContent(7)">BusinessCity</a>
                        <a href="#" class="template-field appended-field" id="templateField8" onclick="addFieldToTemplateContent(8)">BusinessPostCode</a>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="campaignFrequency">{{ trans('salon.when_to_send_emails') }}</label>
                    <select name="campaign_frequency" id="campaignFrequency" class="form-control campaign-frequency">
                        <option value="0">{{ trans('salon.only_once') }}</option>
                        <option value="1">{{ trans('salon.every_week') }}</option>
                        <option value="2">{{ trans('salon.every_second_week') }}</option>
                        <option value="3">{{ trans('salon.every_month') }}</option>
                        <option value="4">{{ trans('salon.every_two_months') }}</option>
                        <option value="5">{{ trans('salon.every_six_months') }}</option>
                        <option value="6">{{ trans('salon.every_year') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="campaignTime">{{ trans('salon.time_to_send_emails') }}</label>
                    <select name="campaign_time" id="campaignTime" class="form-control campaign_time">
                        @foreach($time_list as $mtime=>$time)
                            <option value="{{$mtime}}" @if($mtime === '12:00') selected @endif>{{ $time }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="submit" class="btn btn-primary">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>