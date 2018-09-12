<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{ csrf_token() }}"/>

    <title>{{ $salon->business_name }}</title>

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/plugins/bootstrapselect/bootstrapselect.css') }}
    {{ HTML::style('font-awesome/css/font-awesome.min.css') }}
    {{ HTML::style('css/custom.css') }}
    {{ HTML::style('css/salon-website.css') }}
    {{ HTML::style('css/plugins/datepicker/datepicker.css') }}
    {{ HTML::style('css/plugins/checkbox/bootstrap-checkbox.css') }}
    {{ HTML::style('css/plugins/toastr/toastr.min.css') }}
    {{ HTML::style('css/plugins/sweetalert/sweetalert.css') }}

    <script type="text/javascript">
        var ajax_url = '<?php echo URL::to('/'); ?>/';
    </script>
</head>

<body id="page-top" class="client-booking-page no-skin-config">

<section id="bookingSection" class="client-web-booking">
    <input type="hidden" id="staffSelectionCheck">
    <div class="container">
        <div class="row border-bottom white-bg page-heading">
            <div class="booking-steps text-center m-t m-b">
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
    </div>
</section>

<section id="clientBookingProcess">
    <div class="client-loader hidden">
        <h2 class="text-center">{{ trans('salon.please_wait') }}</h2>
        <div class="loader"></div>
    </div>
    <div class="booking-process">
        <div class="ibox-content alt">
            <div id="bookingOptions" class="wrapper wrapper-content">
                <div class="container">
                    <div class="col-lg-12 text-center m-t">
                        <input type="hidden" id="locationId" name="location_id" @if(isset($location)) value="{{ $location->id }}" @else value="null" @endif>
                        {{ Form::hidden('calendar_date', date('d.m.Y.'), array('id' => 'calendarDate')) }}
                        <div class="booking-step-service">
                            @include('partials.booking.clientSelectService')
                        </div>
                        @if($booking_options->staff_selection === 0)
                        <div class="booking-step-staff">
                            @include('partials.booking.selectStaff')
                        </div>
                        @endif
                        <div class="booking-step-time">
                            @include('partials.booking.selectTime')
                        </div>
                        <div class="client-submit-info">
                            @include('partials.booking.clientSubmitInfo')
                        </div>
                        <input id="dateCalendar" class="m-b btn btn-default pick-date" placeholder="{{ trans('salon.select_date') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{ HTML::script('js/jquery-3.1.1.min.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/website/salonWebsite.js') }}
{{ HTML::script('js/plugins/bootstrapselect/bootstrapselect.js') }}
{{ HTML::script('js/plugins/momentjs/moment.min.js') }}
{{ HTML::script('js/plugins/datepicker/datepicker.js') }}
{{ HTML::script('js/booking/booking.js') }}
{{ HTML::script('js/plugins/toastr/toastr.min.js') }}
{{ HTML::script('js/plugins/sweetalert/sweetalert.min.js') }}

<script>
    var location_count = {{ count($location_list) }};
    @if($location != null)
        var section = {{ $location->id }};
    @else
        var section = null;
    @endif
    var first_location = {{ $first_location }};
    var confirm_booking = '{{ trans('salon.confirm_booking') }}';
    var staff_selection = {{ $booking_options->staff_selection }};
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
    var week_start = {{ $week_start }};
    var trans_register = '{{ trans('auth.register') }}';
    var trans_login = '{{ trans('auth.login') }}';
    var trans_submit_without_account = '{{ trans('salon.submit_without_account') }}';
    var trans_back = '{{ trans('salon.back') }}';
    var trans_submit = '{{ trans('salon.submit') }}';
    var passwords_mismatch = '{{ trans('salon.passwords_mismatch') }}';
    var trans_first_name = '{{ trans('auth.first_name') }}';
    var trans_last_name = '{{ trans('auth.last_name') }}';
    var booking_success = '{{ trans('salon.booking_success') }}';
    var booking_success_action = '{{ trans('salon.booking_success_action') }}';
    var return_to_homepage = '{{ trans('salon.return_to_homepage') }}';
    var staff_not_selected = '{{ trans('salon.staff_not_selected') }}';
    var services_not_selected = '{{ trans('salon.services_not_selected') }}';
    var points_trans = '{{ trans('salon.points_trans') }}';
    var trans_accept_gdpr = '{{ trans('salon.gdpr_trans') }}';
    var privacy_policy_route = '{{ route('privacyPolicy', $salon->unique_url) }}';
    var privacy_policy = '{{ trans('salon.gdpr_trans') . ' ' . trans('salon.terms_and_conditions') }}';
    var select_category = '{{ trans('salon.select_category_s') }}';

    toastr.options = {
      "positionClass": "toast-bottom-right",
      "onclick": null,
      "fadeIn": 300,
      "fadeOut": 1000,
      "timeOut": 5000,
      "extendedTimeOut": 1000
    }
    
    $('#staffSelectionCheck').val({{ $booking_options->staff_selection }});
    
</script>
</body>
</html>
