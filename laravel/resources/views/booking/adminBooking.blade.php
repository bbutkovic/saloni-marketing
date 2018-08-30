@extends('main')

@section('styles')
{{ HTML::style('css/plugins/datepicker/datepicker.css') }}
{{ HTML::style('css/adminCustom.css') }}
{{ HTML::style('css/plugins/checkbox/bootstrap-checkbox.css') }}
{{ HTML::style('css/plugins/bootstrapselect/bootstrapselect.css') }}
@endsection

@section('scripts')
{{ HTML::script('js/plugins/bootstrapselect/bootstrapselect.js') }}
{{ HTML::script('js/plugins/momentjs/moment.min.js') }}
{{ HTML::script('js/plugins/datepicker/datepicker.js') }}
{{ HTML::script('js/booking/booking.js') }}
{{ HTML::script('js/clients.js') }}
@endsection


@section('content')

    @if($booking_options != null && $calendar_settings != null && $calendar_options != null)
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="row booking-steps text-center m-t">
            @if($booking_options->staff_selection === 0)
            <div class="step-wrap col-xs-4">
                <h1 class="booking-step step-1 active">1</h1>
                <h2 class="booking-step-info step-1 active">{{ trans('salon.select_service') }}</h2>
            </div>
            <div class="step-wrap col-xs-4">
                <h1 class="booking-step step-2 disabled">2</h1>
                <h2 class="booking-step-info step-2 disabled">{{ trans('salon.select_staff') }}</h2>
            </div>
            <div class="step-wrap col-xs-4">
                <h1 class="booking-step step-3 disabled">3</h1>
                <h2 class="booking-step-info step-3 disabled">{{ trans('salon.select_time') }}</h2>
            </div>
            @else
            <div class="step-wrap col-xs-6">
                <h1 class="booking-step step-1 active">1</h1>
                <h2 class="booking-step-info step-1 active">{{ trans('salon.select_service') }}</h2>
            </div>
            <div class="step-wrap col-xs-6">
                <h1 class="booking-step step-3 disabled">2</h1>
                <h2 class="booking-step-info step-3 disabled">{{ trans('salon.select_time') }}</h2>
            </div>
            @endif
        </div>
    </div>
    <div class="client-loader hidden">
        <h2 class="text-center">{{ trans('salon.please_wait') }}</h2>
        <div class="loader"></div>
    </div>
    <div class="booking-process row">
        <div class="ibox-content alt">
            <div id="bookingOptions" class="wrapper wrapper-content">
                <div class="col-lg-12 text-center m-t">
                    {{ Form::hidden('location_id', Auth::user()->location_id, array('id' => 'locationId')) }}
                    {{ Form::hidden('calendar_date', date('d.m.Y.'), array('id' => 'calendarDate')) }}
                    <div class="booking-step-service">
                        @include('partials.booking.selectService')
                    </div>
                    @if($booking_options->staff_selection === 0)
                    <div class="booking-step-staff">
                        @include('partials.booking.selectStaff')
                    </div>
                    @endif
                    <div class="booking-step-time">
                        @include('partials.booking.selectTime')
                    </div>
                    <input id="dateCalendar" class="m-b btn btn-default pick-date" placeholder="{{ trans('salon.select_date') }}">
                </div>
            </div>
        </div>
    </div>
    
    @include('partials.booking.addNewClient')
<script>
    var confirm_booking = '{{ trans('salon.confirm_booking') }}';
    var user_location = {{ Auth::user()->location_id }};
    var staff_selection = {{ $booking_options->staff_selection }};
    var week_start = {{ $week_start }};
    var select_client = '{{ trans('salon.select_client') }}';
    var no_clients = '{{ trans('salon.no_clients') }}';
    var next = '{{ trans('salon.next') }}';
    var next_date = '{{ trans('salon.next_date') }}';
    var multiple_staff = {{ $booking_options->multiple_staff }};
    var select_staff = '{{ trans('salon.select_staff') }}';
    var select_staff_randomly_desc = '{{ trans('salon.select_staff_randomly_desc') }}';
    var select_staff_randomly = '{{ trans('salon.select_staff_randomly') }}';
    var price_calc = '{{ trans('salon.total_price') }}';
    var selected_booking_for = '{{ trans('salon.selected_booking_for') }}';
    var salon_currency = '{{ $currency }}';
    var client_name = '{{ $salon->client_settings->name_format }}';
    var discount_code_trans = '{{ trans('salon.discount_code_trans') }}';
    var waiting_list_status = {{ $calendar_options->waiting_list }};
    var password_trans = '{{ trans('auth.password') }}';
    var password_confirm_trans = '{{ trans('auth.password_confirmation') }}';
    var trans_back = '{{ trans('salon.back') }}';
    var trans_submit = '{{ trans('salon.submit') }}';
    var booking_success = '{{ trans('salon.booking_success') }}';
    var booking_success_action = '{{ trans('salon.booking_success_action') }}';
    var return_to_homepage = '{{ trans('salon.return_to_homepage') }}';
    var staff_not_selected = '{{ trans('salon.staff_not_selected') }}';
    var client_not_selected = '{{ trans('salon.client_not_selected') }}';
    var services_not_selected = '{{ trans('salon.services_not_selected') }}';
    var admin_booking = 1;
    var add_new_booking = '{{ trans('salon.add_new_booking') }}';
    var go_to_calendar = '{{ trans('salon.go_to_calendar') }}';
</script>
@else
<div class="row text-center m-t">
    <h1 class="text-muted">{{ trans('salon.booking_options_not_set') }}</h1>
    @if($booking_options === null)
    <a href="{{ route('onlineBooking') }}" class="btn btn-default m-t">{{ trans('salon.online_booking') }}</a>
    @endif
    @if($calendar_settings === null || $calendar_options === null)
    <a href="{{ route('calendarSettings') }}" class="btn btn-default m-t">{{ trans('salon.calendar_settings') }}</a>
    @endif

</div>
@endif

@endsection