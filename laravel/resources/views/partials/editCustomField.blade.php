<div class="modal fade" id="editCustomField" tabindex="-1" role="dialog" aria-labelledby="editCustomField" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.edit_custom_field') }}</h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('id' => 'editCustomField')) }}
                {{ Form::hidden('field_id', null, array('id' => 'fieldId')) }}
                {{ Form::hidden('field_type', null, array('id' => 'editFieldType')) }}
                
                <div class="row">
                    <div class="col-lg-12 edit-field-wrap">
                        <h3 class="text-center date m-t m-b"></h3>
                        <div class="form-group">
                            <label for="fieldTitle">{{ trans('salon.title') }}</label>
                            <input type="text" id="fieldTitle" class="form-control" name="field_title" required>
                        </div>
                    </div>
                    <div class="col-lg-12 select-options-wrap">
                        <button type="button" class="btn btn-success" id="spawnBtn"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ trans('salon.update') }}</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>