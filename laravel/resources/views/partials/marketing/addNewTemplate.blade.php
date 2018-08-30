<div class="modal fade" id="addNewTemplateModal" tabindex="-1" role="dialog" aria-labelledby="addNewTemplateForm" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.add_new_template') }}</h4>
            </div>
            {{ Form::open(array('route' => 'addNewTemplate', 'id' => 'addNewTemplateForm', 'files' => 'true')) }}
            <div class="modal-body">
                <div class="form-group">
                    <label for="templateName">{{ trans('salon.template_name') }}</label>
                    {{ Form::text('template_name', null, array('id' => 'templateName', 'class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    <label for="templateSubject">{{ trans('salon.template_subject') }}</label>
                    {{ Form::text('template_subject', null, array('id' => 'templateSubject', 'class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    <label for="templateFor">{{ trans('salon.template_for') }}</label>
                    <select name="template_for" id="templateFor" class="form-control template-for">
                        <option value="0" selected disabled>{{ trans('salon.select_medium') }}</option>
                        <option value="1">Email</option>
                        <option value="2">SMS</option>
                        <option value="3">Viber</option>
                        <option value="4">Facebook messenger</option>
                        <option value="5">Push notification</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="templateType">{{ trans('salon.template_type') }}</label>
                    <select name="template_type" id="templateType" class="form-control template-type">
                        <option value="0" selected disabled>{{ trans('salon.select_template_type') }}</option>
                        <option value="1">{{ trans('salon.template_appointment_reminders') }}</option>
                        <option value="2">{{ trans('salon.template_confirmation') }}</option>
                        <option value="3">{{ trans('salon.template_reschedules') }}</option>
                        <option value="4">{{ trans('salon.template_cancelations') }}</option>
                        <option value="5">{{ trans('salon.template_birthday') }}</option>
                        <option value="6">{{ trans('salon.template_loyalty_points') }}</option>
                        <option value="7">{{ trans('salon.template_new_client') }}</option>
                        <option value="8">{{ trans('salon.template_marketing_campaign') }}</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tinymce">{{ trans('salon.template_content') }}</label>
                    <div class="template-content">
                        <textarea id="tinymce" class="tinymce-textarea" name="editordata"></textarea>
                        <input name="image" type="file" id="upload" class="hidden appended-textarea" onchange="">
                    </div>
                </div>
                <div class="template-content-fields">
                    <small class="text-muted">{{ trans('salon.template_fields_desc') }}</small>
                    <div class="fields-wrap"></div>
                </div>
                <div id="templateAttachment" class="template-attachment hidden">
                    <div class="row">
                        <img class="salon-logo-img">
                        <div class="form-group m-l m-r">
                            <label for="mail_attachment">{{ trans('salon.attachment') }}</label>
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">{{ trans('salon.select_attachment') }}</span>
                                    <input type="file" name="mail_attachment">
                                </span>
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                            </div>
                        </div>
                    </div>
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