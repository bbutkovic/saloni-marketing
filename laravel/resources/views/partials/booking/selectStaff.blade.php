<div class="staff-container col-md-6">
    <h4 class="text-muted">{{ trans('salon.select_staff') }}</h4>
</div>

@if($booking_options->staff_selection === 0)
<div id="timeSelection" class="col-md-6">
    <select class="hidden" name="randomStaff" id="randomStaff"></select>
    <div id="datepicker" class="datepicker-booking"></div>
    <input type="hidden" id="selectedDate">
</div>
@endif