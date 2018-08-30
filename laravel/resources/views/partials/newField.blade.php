<div class="modal fade" id="newFieldModal" tabindex="-1" role="dialog" aria-labelledby="newFieldModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="newFieldModalLabel">{{ trans('salon.add_new_field') }}</h4>
            </div>
            {{ Form::open(array('id' => 'addNewFieldForm')) }}
                {{ Form::hidden('field_location', null, array('id' => 'fieldLocation')) }}
                <div class="modal-body new-field-creation">
                    <div class="row">
                        <div class="form-group">
                            <label for="fieldName">{{ trans('salon.field_name') }}</label>
                            {{ Form::text('main_field_name', null, array('id' => 'fieldName', 'class' => 'form-control', 'required')) }}
                        </div>

                        <div class="form-group">
                            <label for="fieldSelectType">{{ trans('salon.field_type') }}</label>
                            {{ Form::select('field_input_type', ['1' => trans('salon.field_text'), '2' => trans('salon.field_multiple_select')], null, array('id' => 'fieldSelectType', 'class' => 'form-control')) }}
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