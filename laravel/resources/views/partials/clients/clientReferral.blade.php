<div class="modal fade" id="clientReferral" tabindex="-1" role="dialog" aria-labelledby="clientReferral" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.add_referral') }}</h4>
            </div>
            {{ Form::open(array('id' => 'saveClientReferral')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="referral_name">{{ trans('salon.referral_name') }}</label>
                        {{ Form::text('referral_name', null, array('id' => 'referralName', 'class' => 'form-control')) }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="button" class="btn btn-primary saveReferral">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div class="modal fade" id="updateClientReferral" tabindex="-1" role="dialog" aria-labelledby="updateClientReferral" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.update_referral') }}</h4>
            </div>
            {{ Form::open(array('id' => 'saveClientReferral')) }}
            {{ Form::hidden('referral_id', null, array('id' => 'referralId')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group">
                        <label for="referral_name">{{ trans('salon.referral_name') }}</label>
                        {{ Form::text('referral_name', null, array('id' => 'updateReferralName', 'class' => 'form-control')) }}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="button" class="btn btn-primary saveReferral">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>