<div class="modal fade" id="requirePin" tabindex="-1" role="dialog" aria-labelledby="requirePin" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.insert_pin') }}</h4>
            </div>
            {{ Form::open(array('route' => 'submitPin', 'id' => 'insert_pin')) }}
            <div class="modal-body">
                
                <div class="row">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="pin">PIN <small class="text-muted">({{ trans('salon.pin_desc') }})</small></label>
                            {{ Form::text('pin', null, array('id' => 'pin', 'class' => 'form-control', 'required')) }}
                        </div>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="submit" class="btn btn-primary" onclick="addStaffMember()">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>