@extends('main')

@section('styles')
    {{ HTML::style('css/plugins/datepicker/datepicker.css') }}
@endsection

@section('scripts')
    {{ HTML::script('js/stats.js') }}
    {{ HTML::script('js/plugins/datepicker/datepicker.js') }}
    {{ HTML::script('js/plugins/chartjs/chart.min.js') }}
@endsection

@section('content')

    @if($salon != null)

        @if(!Auth::user()->hasRole('user'))

            <div class="ibox float-e-margins">
                <div class="row wrapper border-bottom white-bg page-heading">
                    <div class="col-lg-12">
                        <h2 class="text-center section-heading">{{ trans('menu.dashboard') }}</h2>
                    </div>
                </div>
            </div>

            @if($monthly_bookings != 'undefined')
            <div class="wrapper wrapper-content animated fadeInRight">
                <div class="row date-picker m-b">
                    <div class="input-group date-group">
                        <input type="text" class="form-control start-date-picker" value="{{ date('m/d/Y', strtotime($stats_date['start_date'])) }}">
                        <div class="input-group-addon">to</div>
                        <input type="text" class="form-control end-date-picker" value="{{ date('m/d/Y', strtotime($stats_date['end_date'])) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins next-booking-wrap">
                            <div class="ibox-title">
                                <h5>{{ trans('salon.next_booking') }}</h5>
                                <hr>
                                @if($next_booking != null)
                                <p class="text-center">{{ $next_booking->getDateAttribute() }}</p>
                                <h4 class="text-center"><strong>{{ $next_booking->getStartTimeAttribute() . ' - ' . $next_booking->getEndTimeAttribute() }}</strong></h4>
                                <h4 class="text-center">{{ $next_booking->service->service_details->name }} - <strong>{{ $next_booking->staff->user_extras->first_name . ' ' . $next_booking->staff->user_extras->last_name}}</strong></h4>
                                <h4 class="text-center">{{ trans('salon.client') }}:  <a href="{{ route('viewClientProfile', $next_booking->client_id) }}"><strong>{{ $next_booking->client->first_name . ' ' . $next_booking->client->last_name }}</strong></a></h4>
                                @else
                                <h2 class="text-center">{{ trans('salon.no_upcoming_bookings') }}</h2>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title today-bookings-wrap">
                                <h5>{{ trans('salon.today_bookings') }}</h5>
                                <hr>
                                <h1 class="text-center">{{ $today_bookings }}</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title new-clients-wrap">
                                <h5>{{ trans('salon.new_clients') }}</h5>
                                <hr>
                                @if($new_clients['male'] === 0 && $new_clients['female'] === 0 && $new_clients['undefined'] === 0)
                                    <h1 class="text-center">0</h1>
                                @else
                                <div>
                                    <canvas id="todayBookingsPie" height="101"></canvas>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>{{ trans('salon.monthly_statistics') }}</h5>
                            </div>
                            <div class="ibox-content">
                                <div>
                                    <canvas id="monthlyBookingsChart" height="140"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ibox float-e-margins">
                            <div class="ibox-title">
                                <h5>{{ trans('salon.monthly_income') }}</h5>
                            </div>
                            <div class="ibox-content">
                                <div>
                                    <canvas id="monthlyIncomeChart" height="140"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif

    @else
    
    @role('salonadmin')
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-12">
                    <h2 class="text-center section-heading">{{ trans('salon.create_salon_warning') }}</h2>
                    <h4 class="text-center">{{ trans('salon.create_salon_desc') }}</h4>
                </div>
            </div>
            
            <div class="salon-registration">
                {{ Form::open(array('route' => 'createSalon', 'method' => 'post', 'id' => 'register-salon', 'class' => 'm-t')) }}
                <div class="row">
                    <div class="col-lg-6">
                        {{ Form::hidden('with_location', 1, array('id' => 'with_location')) }}
                        <div class="form-group">
                            <label for="business_name">{{ trans('salon.business_name') }}</label>
                            {{ Form::text('business_name', null, array('id' => 'business_name', 'class' => 'form-control', 'required')) }}
                            <small class="text-danger">{{ $errors->first('business_name') }}</small>
                        </div>
                        <div class="form-group">
                            <label for="business_email">{{ trans('salon.business_email') }}</label>
                            {{ Form::email('business_email', null, array('id' => 'business_email', 'class' => 'form-control', 'required')) }}
                            <small class="text-danger">{{ $errors->first('business_email') }}</small>
                        </div>
                        <div class="form-group">
                            <label for="salon_address">{{ trans('salon.salon_address') }}</label>
                            {{ Form::text('salon_address', null, array('id' => 'salon_address', 'class' => 'form-control', 'required')) }}
                            <small class="text-danger">{{ $errors->first('salon_address') }}</small>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="salon_city">{{ trans('salon.salon_city') }}</label>
                            {{ Form::text('salon_city', null, array('id' => 'salon_city', 'class' => 'form-control', 'required')) }}
                            <small class="text-danger">{{ $errors->first('salon_city') }}</small>
                        </div>
                        <div class="form-group">
                            <label for="salon_zip">{{ trans('salon.salon_zip') }}</label>
                            {{ Form::text('salon_zip', null, array('id' => 'salon_zip', 'class' => 'form-control', 'required')) }}
                            <small class="text-danger">{{ $errors->first('salon_zip') }}</small>
                        </div>
                        <div class="form-group">
                            <label for="salon_country">{{ trans('salon.salon_country') }}*</label>
                            <select name="salon_country" class="form-control" id="salon_country">
                                @foreach($countries as $country)
                                <option value="{{ $country->country_identifier }}" @if(isset($location->country) && ($location->country == $country->country_identifier)) ? selected : null @endif >{{ $country->country_local_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <button class="btn btn-success m-t">{{ trans('salon.save_salon') }}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    @endrole
    
    @endif
<script>
    var swal_title = '{{ trans("salon.with_location") }}';
    var swal_desc = '{{ trans("salon.location_desc") }}';
    var swal_with_location = '{{ trans("salon.create_location") }}';
    var swal_without_location = '{{ trans("salon.without_location") }}';
    var completed_bookings = [];
    var cancelled_bookings = [];
    var completed_income = [];
    var months = [];

    $('.start-date-picker').datepicker({});
    $('.end-date-picker').datepicker({});

    @if($monthly_bookings != 'undefined')
    @foreach($monthly_bookings as $booking)
        completed_bookings.push({{ $booking['completed'] }});
        cancelled_bookings.push({{ $booking['cancelled'] }});
        completed_income.push({{ $booking['income'] }});
    @endforeach

    @foreach($month_list as $month)
        months.push('{{ $month }}');
    @endforeach
    console.log(months);
    var moneyData = {
        labels: months,
        datasets: [
            {
                label: "{{ trans('salon.monthly_income') . ' (' . $salon->salon_currency->code . ')' }}",
                backgroundColor: 'rgba(26,179,148,0.5)',
                borderColor: "rgba(26,179,148,0.7)",
                pointBackgroundColor: "rgba(26,179,148,1)",
                pointBorderColor: "#fff",
                data: completed_income
            }
        ]
    };

    var lineOptions = {
        responsive: true
    };

    var ctx = document.getElementById("monthlyIncomeChart").getContext("2d");
    new Chart(ctx, {type: 'line', data: moneyData, options:lineOptions});

    var bookingsData = {
        labels: months,
        datasets: [
            {
                label: "{{ trans('salon.completed_bookings') }}",
                backgroundColor: "rgba(26,179,148,1)",
                pointBorderColor: "#fff",
                data: completed_bookings
            },
            {
                label: "{{ trans('salon.cancelled_bookings') }}",
                backgroundColor: 'rgba(26,179,148,0.5)',
                borderColor: "rgba(26,179,148,0.7)",
                pointBackgroundColor: 'rgba(220, 220, 220, 0.5)',
                pointBorderColor: "#fff",
                data: cancelled_bookings
            }
        ]
    };

    var barOptions = {
        responsive: true
    };

    var ctx2 = document.getElementById("monthlyBookingsChart").getContext("2d");
    new Chart(ctx2, {type: 'bar', data: bookingsData, options:barOptions});

    var todayBookingsPie = {
        labels: ["{{ trans('salon.men') }}","{{ trans('salon.women') }}","{{ trans('salon.undefined') }}" ],
        datasets: [{
            data: [{{ $new_clients['male'] }},{{ $new_clients['female'] }},{{ $new_clients['undefined'] }}],
            backgroundColor: ["#a3e1d4","#dedede","#b5b8cf"]
        }]
    } ;

    var doughnutOptions = {
        responsive: true,
        legend: {
            position: 'left'
        }
    };

    var ctx4 = document.getElementById("todayBookingsPie").getContext("2d");
    new Chart(ctx4, {type: 'doughnut', data: todayBookingsPie, options:doughnutOptions});
    @endif
</script>
@endsection