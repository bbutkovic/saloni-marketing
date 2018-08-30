@if($booking_options->staff_selection != 0)
<div class="row">
    <div id="timeSelectionSkipStaff" class="col-md-6">
        <select class="hidden" name="randomStaff" id="randomStaff"></select>
        <div id="datepicker" datepicker-booking></div>
        <input type="hidden" id="selectedDate">
    </div>
    
    <div id="availableForBooking" class="user-settings-wrapper col-md-6">
        <div class="table-responsive table-available-hours">
            <table class="table table-striped table-bordered table-hover d-table">
                <thead>
                    <tr>
                        <th class="text-center">{{ trans('salon.time_from') }}</th>
                    </tr>
                </thead>
                <tbody class="table-schedule">
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div id="availableForBooking" role="tablist" aria-multiselectable="true" class="user-settings-wrapper">
    <div id="collapseTimesTable" class="ibox">
        <div class="ibox-title" role="tab" id="headingOne">
            <a class="collapse-link" data-toggle="collapse" data-parent=".accordionOne" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><h5>{{ trans('salon.displaying_sch_for') }} <i class="fa fa-chevron-up"></i></h5></a>
        </div>
        <div id="collapseOne" class="panel-collapse collapse in ibox-content" role="tabpanel" aria-labelledby="headingOne">
            <div class="table-responsive table-available-hours">
                <table class="table table-striped table-bordered table-hover d-table">
                    <thead>
                        <tr>
                            <th class="text-center">{{ trans('salon.time_from') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-schedule">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

<div id="selectedBookingTime" class="user-settings-wrapper accordionTwo" role="tablist" aria-multiselectable="true">
    <div id="collapseBookingInfo" class="ibox">
        <div class="ibox-title" role="tab" id="headingTwo">
            <a class="collapse-link" data-toggle="collapse" data-parent=".accordionTwo" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo"><h5>{{ trans('salon.selected_time') }} <i class="fa fa-chevron-up"></i></h5></a>
        </div>
        <div id="collapseTwo" class="ibox-content booking-info text-center panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
            {{ Form::open(array('class' => 'booking-confirm m-t', 'id' => 'submitBookingForm')) }}
                {{ Form::hidden('booking_location', null, array('id' => 'bookingLocation')) }}
                {{ Form::hidden('booking_date', null, array('id' => 'bookingDate')) }}
                {{ Form::hidden('booking_from', null, array('id' => 'bookingFrom')) }}
                {{ Form::hidden('booking_to', null, array('id' => 'bookingTo')) }}
                {{ Form::hidden('client_id', null, array('id' => 'clientId')) }}
                {{ Form::hidden('total_price', null, array('id' => 'totalPrice')) }}
                {{ Form::hidden('points_awarded', null, array('id' => 'awardedPoints')) }}
                {{ Form::hidden('client_booking', $client_check, array('id' => 'clientBookingCheck')) }}
                {{ Form::hidden('loyalty_status', null, array('id' => 'clientLoyaltyStatus')) }}
                {{ Form::hidden('loyalty_type', null, array('id' => 'clientLoyaltyType')) }}
                <div class="row select-client-row">
                    <div class="col-lg-12 text-center m-t">
                        <button type="button" class="btn btn-default" id="addNewClient">{{ trans('salon.add_new_client') }}</button>
                        <select name="clientList" id="selectClient" class="selectpicker" required data-live-search="true"></select>
                    </div>
                </div>
                <div class="customer-info">
                    
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
