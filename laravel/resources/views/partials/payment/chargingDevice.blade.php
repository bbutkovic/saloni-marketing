<div class="modal fade" id="chargingDeviceModal" tabindex="-1" role="dialog" aria-labelledby="chargingDeviceModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Dodaj novi naplatni uređaj</h4>
            </div>
            {{ Form::open(array('id' => 'addChargingDevice', 'class' => 'm-t m-b')) }}
                {{ Form::hidden('device_id', null, array('id' => 'deviceId')) }}
                <div class="row m-l m-r">
                    <div class="form-group">
                        <label for="chargingDeviceLabel">Oznaka naplatnog uređaja</label>
                        {{ Form::number('device_label', null, array('id' => 'chargingDeviceLabel', 'class' => 'form-control')) }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                    <button type="button" class="btn btn-success" onclick="submitChargingDevice()">{{ trans('salon.submit') }}</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>