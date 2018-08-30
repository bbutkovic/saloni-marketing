<div class="modal fade" id="clientLabel" tabindex="-1" role="dialog" aria-labelledby="clientLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.add_label') }}</h4>
            </div>
            {{ Form::open(array('id' => 'saveClientLabel')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="label_name">{{ trans('salon.label_name') }}</label>
                        {{ Form::text('label_name', null, array('id' => 'labelName', 'class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="label_color">{{ trans('salon.label_color') }}</label>
                        <input type="text" id="spectrumLabel" value="#4206A9" name="label_color">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="button" class="btn btn-primary saveLabel">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div class="modal fade" id="updateLabel" tabindex="-1" role="dialog" aria-labelledby="updateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.update_label') }}</h4>
            </div>
            {{ Form::open(array('id' => 'updateLabelForm')) }}
            {{ Form::hidden('label_id', null, array('id' => 'labelId')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="label_name">{{ trans('salon.label_name') }}</label>
                        {{ Form::text('label_name', null, array('id' => 'updateLabelname', 'class' => 'form-control')) }}
                    </div>
                    <div class="form-group">
                        <label for="label_color">{{ trans('salon.label_color') }}</label>
                        <input type="text" id="spectrumUpdateLabel" name="label_color">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="button" class="btn btn-primary saveLabel">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>