<div class="modal fade" id="rescheduleBookingModal" tabindex="-1" role="dialog" aria-labelledby="rescheduleBooking" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('salon.reschedule') }}</h4>
            </div>
            {{ Form::open(array('id' => 'rescheduleBooking')) }}
            {{ Form::hidden('booking_id', null, array('id' => 'bookingId')) }}
            {{ Form::hidden('booking_date', null, array('id' => 'bookingDate')) }}
            {{ Form::hidden('booking_from', null, array('id' => 'bookingFrom')) }}
            {{ Form::hidden('booking_to', null, array('id' => 'bookingTo')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group booking-group">
                        <h4 class="text-muted">{{ trans('salon.reschedule_info') }} <strong class="service-list"></strong></h4>
                    </div>
                    <div class="form-group edit-date">
                        <div id="datepicker"></div>
                    </div>
                    <div class="table-responsive table-available-hours hidden">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="text-center">{{ trans('salon.time_from') }}</th>
                            </tr>
                            </thead>
                            <tbody class="table-schedule">
                            </tbody>
                        </table>
                    </div>
                    <h4 class="text-center text-muted new-time m-t"></h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="button" class="btn btn-primary" onclick="submitReschedule()">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>