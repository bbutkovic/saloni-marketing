<div class="modal fade" id="cancelBookingModal" tabindex="-1" role="dialog" aria-labelledby="cancelBooking" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('salon.cancel_booking_trans') }}</h4>
            </div>
            {{ Form::open(array('id' => 'cancelBooking')) }}
            {{ Form::hidden('booking_id', null, array('id' => 'bookingIdCancel')) }}
            <div class="modal-body">
                <div class="row services-list">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="button" class="btn btn-primary" onclick="submitCancel()">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>