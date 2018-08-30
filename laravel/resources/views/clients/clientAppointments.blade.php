@extends('main')

<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId            : '387717418355900',
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v3.1'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

@section('styles')
    {{ HTML::style('css/plugins/datepicker/datepicker.css') }}
    {{ HTML::style('css/stripe.css') }}
@endsection

@section('scripts')
    {{ HTML::script('js/plugins/dataTables/datatables.min.js') }}
    {{ HTML::script('js/plugins/datepicker/datepicker.js') }}
    {{ HTML::script('js/clients/clients.js') }}
@endsection


@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading pull-left">{{ trans('salon.my_appointments') }}</h2>
        </div>
    </div>

    {{ Session::get('Success') }}
    <div id="clientAppointments">
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-sm-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <h3 class="upcoming-bookings">{{ trans('salon.upcoming_bookings') }}</h3>
                            <div class="upcoming-bookings-wrap"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <h3 class="booking-info">{{ trans('salon.general_booking_info') }}</h3>
                            <h5><strong>{{ trans('salon.bookings_made') }}: </strong>{{ $stats[0]['appointments_made'] }}</h5>
                            <h5><strong>{{ trans('salon.bookings_completed') }}: </strong>{{ $stats[0]['appointments_complete'] }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table data-order='[[ 1, "asc" ]]' class="table table-bordered table-hover d-table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th class="text-center">{{ trans('salon.booking_type') }}</th>
                                        <th class="text-center">{{ trans('salon.booked_service') }}</th>
                                        <th class="text-center">{{ trans('salon.total_price') }}</th>
                                        <th class="text-center">{{ trans('salon.location') }}</th>
                                        <th class="text-center">{{ trans('salon.date') }}</th>
                                        <th class="text-center">{{ trans('salon.time') }}</th>
                                        <th class="text-center">{{ trans('salon.status') }}</th>
                                        <th class="text-center">{{ trans('salon.payment_status') }}</th>
                                        <th class="text-center">{{ trans('salon.action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody class="staff-table">
                                    @foreach($bookings as $booking)
                                        <tr data-id="{{ $booking['id'] }}" @if($booking['status'] === 'status_cancelled') style="background-color: rgba(0,0,0,.03)" @endif>
                                            <td>{{ $booking['id'] }}</td>
                                            <td>{{ trans('salon.type_'.$booking['type']) }}</td>
                                            <td>{{ $booking['service'] }}</td>
                                            <td>{{ $booking['price'] }}</td>
                                            <td>{{ $booking['location'] }}</td>
                                            <td>{{ $booking['date'] }}</td>
                                            <td>{{ $booking['start'] . ' - ' . $booking['end'] }}</td>
                                            <td>{{ trans('salon.'.$booking['status']) }}</td>
                                            <td>@if($booking['payment_status'] === 1) {{ trans('salon.paid') }} @else {{ trans('salon.not_paid') }}@endif</td>
                                            <td class="user-options">
                                                @if($booking['options'] != 0)
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.reschedule') }}" onclick="rescheduleBooking({{ $booking['id'] }})">
                                                        <i class="fa fa-pencil table-profile"></i>
                                                    </a>
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.add_to_calendar') }}" onclick="addBookingToCalendar({{ $booking['id'] }})">
                                                        <i class="fa fa-calendar table-profile"></i>
                                                    </a>
                                                    @if($booking['status'] != 'status_paid' || $booking['status'] != 'status_complete')
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.cancel_booking') }}" onclick="cancelBooking({{ $booking['id'] }})">
                                                        <i class="fa fa-ban table-delete"></i>
                                                    </a>
                                                    @endif
                                                    @if($booking['online_payments'] === 1 && $booking['payment_status'] === 0 && ($booking['status'] != 'status_complete' && $booking['status'] != 'status_cancelled'))
                                                    <a href="#" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.payment') }}" onclick="openPaymentForm({{ $booking['id'] }})">
                                                        <i class="fa fa-usd table-pay"></i>
                                                    </a>
                                                    @endif
                                                @else
                                                    <p>{{ trans('salon.no_options') }}</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.clients.rescheduleBooking')
    @include('partials.clients.cancelBooking')
    @include('partials.clients.paymentForm')
    @include('partials.clients.calendarOptions')

    @section('scripts-footer')
        {{ HTML::script('js/payments/stripe.js') }}
    @endsection

    @if(Session::get('facebook_share'))
    <script>
        function fb_share() {
            FB.getLoginStatus(function(response) {
                if (response.status === 'connected') {
                    FB.ui( {
                        method: 'share',
                        display: 'popup',
                        action_type: 'og.shares',
                        action_properties: JSON.stringify({
                            object: {
                                'og:url': '{{ Session::get('url') }}',
                                'og:title': '{{ Session::get('title') }}',
                                'og:description': '{{ Session::get('description') }}'
                            }
                        })
                    }, function(response) {
                        if (typeof response !== 'undefined') {
                            $.ajax({
                                method: 'post',
                                url: '{{ route('awardSocialPoints') }}',
                                beforeSend: function(request) {
                                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                                },
                                data: {'response':response},
                                success: function(data) {
                                    console.log(response);
                                    if(data.status === 1) {
                                        toastr.success(data.message);
                                    } else {
                                        toastr.error(data.message);
                                    }
                                }
                            });
                        }
                    });
                } else {
                    FB.login(function(response) {
                        if (response.authResponse) {
                            FB.ui( {
                                method: 'share',
                                display: 'popup',
                                action_type: 'og.shares',
                                action_properties: JSON.stringify({
                                    object: {
                                        'og:url': '{{ Session::get('url') }}',
                                        'og:title': '{{ Session::get('title') }}',
                                        'og:description': '{{ Session::get('description') }}'
                                    }
                                })
                            }, function(response) {
                                console.log(response);
                                if (typeof response !== 'undefined') {
                                    $.ajax({
                                        method: 'post',
                                        url: '{{ route('awardSocialPoints') }}',
                                        beforeSend: function(request) {
                                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                                        },
                                        data: {'response':response},
                                        success: function(data) {
                                            if(data.status === 1) {
                                                toastr.success(data.message);
                                            } else {
                                                toastr.error(data.message);
                                            }
                                        }
                                    });
                                }
                            });
                        } else {
                            toastr.error('User cancelled login or did not fully authorize.');
                        }
                    });
                }
            });
        }

        $(document).ready(function(){
            swal({
                html: true,
                title: '{{ trans('salon.facebook_share', ['point' => Session::get('social_points')]) }}',
                text: '{{ trans('salon.facebook_share_text') }}',
                type: "info",
                confirmButtonColor: "#52B3D9",
                confirmButtonText: 'SHARE',
                closeOnConfirm: true,
            }, function (isConfirm) {
                if (isConfirm) {
                    fb_share();
                }
            });
        });

    </script>
    @endif

    <script>
        var no_upcoming_bookings = '{{ trans('salon.upcoming_bookings_empty') }}';
        var new_time_trans = '{{ trans('salon.new_time_trans') }}';
    </script>
@endsection