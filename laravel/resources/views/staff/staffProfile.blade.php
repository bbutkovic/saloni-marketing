@extends('main')

@section('styles')
{{ HTML::style('css/plugins/datepicker/datepicker.css') }}
{{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
@endsection

@section('scripts')
{{ HTML::script('js/staff-schedule.js') }}
{{ HTML::script('js/plugins/jasny/jasny-bootstrap.min.js') }}
{{ HTML::script('js/plugins/dataTables/datatables.min.js') }}
{{ HTML::script('js/plugins/datepicker/datepicker.js') }}
{{ HTML::script('js/plugins/momentjs/moment.min.js') }}
@endsection

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 class="section-heading pull-left">{{ $employee->user_extras->first_name }} {{ $employee->user_extras->last_name }}</h2>
        <div id="booking_radio" class="section-heading pull-right">
            <label for="available">{{ trans('salon.available') }}: </label>
            {{ Form::hidden('uid', $employee->id, array('id' => 'uid')) }}
            <div class="radio radio-info radio-inline">
                <input type="radio" id="available-1" value="1" name="available" @if($employee->user_extras->available_booking === 1) ? checked : null @endif>
                <label for="available-1">{{ trans('salon.radio_yes') }}</label>
            </div>
            <div class="radio radio-inline">
                <input type="radio" id="available-2" value="0" name="available" @if($employee->user_extras->available_booking === 0) ? checked : null @endif>
                <label for="available-2">{{ trans('salon.radio_no') }}</label>
            </div>
        </div>
    </div>
</div>

<div id="location-options" class="user-settings-wrapper">
    <div class="wrapper wrapper-content">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li id="tab-1-li" class="active"><a data-toggle="tab" href="#tab-1">{{ trans('salon.details') }}</a></li>
                <li id="tab-2-li" class=""><a data-toggle="tab" href="#tab-2">{{ trans('salon.work_hours') }}</a></li>
                <li id="tab-3-li" class=""><a data-toggle="tab" href="#tab-3">{{ trans('salon.add_work_hours') }}</a></li>
                <li id="tab-4-li" class=""><a data-toggle="tab" href="#tab-4">{{ trans('salon.service_list') }}</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        <div class="col-lg-12">
                            <div class="ibox">
                                <div class="ibox-title">
                                    <a class="collapse-link"><h5>{{ trans('salon.staff_details') }} <i class="fa fa-chevron-up"></i></h5></a>
                                </div>
                                <div class="ibox-content">
                                    {{ Form::open(array('class' => 'edit-salon', 'files' => 'true')) }}
                                    {{ Form::hidden('user_id', $employee->id, array('id' => 'userId')) }}
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="firstName">{{ trans('auth.first_name') }}</label>
                                            {{ Form::text('first_name', $employee->user_extras->first_name, array('id' => 'firstName', 'class' => 'form-control')) }}
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="lastName">{{ trans('auth.last_name') }}</label>
                                            {{ Form::text('last_name', $employee->user_extras->last_name, array('id' => 'lastName', 'class' => 'form-control')) }}
                                        </div>
                
                                        <div class="form-group">
                                            <label for="birthday">{{ trans('salon.birthday') }}</label>
                                            {{ Form::text('birthday', $employee->user_extras->birthday, array('id' => 'birthday', 'class' => 'pick-birthday form-control')) }}
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="phone">{{ trans('salon.location_mobile_phone') }}</label>
                                            {{ Form::text('phone', $employee->user_extras->phone_number, array('id' => 'phone', 'class' => 'form-control' )) }}
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="address">{{ trans('salon.salon_address') }}</label>
                                            {{ Form::text('address', $employee->user_extras->address, array('id' => 'address', 'class' => 'form-control' )) }}
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="city">{{ trans('salon.salon_city') }}</label>
                                            {{ Form::text('city', $employee->user_extras->city, array('id' => 'city', 'class' => 'form-control' )) }}
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="ibox float-e-margins">
                                                <div class="ibox-title">
                                                    <h3 class="text-center">{{ trans('salon.staff_picture') }}</h3>
                                                    <div class="row">
                                                        <img class="staff-img" src="{{ URL::to('/').$employee->user_extras->photo }}">
                                                        <div class="form-group m-l m-r">
                                                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                                <span class="input-group-addon btn btn-default btn-file">
                                                                    <span class="fileinput-new">{{ trans('salon.select_staff_photo') }}</span>
                                                                    <input type="file" name="staff_photo" id="staffPhoto">
                                                                </span>
                                                                <div class="form-control" data-trigger="fileinput">
                                                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                                    <span class="fileinput-filename"></span>
                                                                </div>
                                                            </div> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 text-center">
                                        <button type="button" class="btn btn-success" onclick="updateUserProfile()">{{ trans('main.update_profile') }}</button>
                                    </div>

                                    {{ Form::close() }}
                                </div>
                            </div>
                            
                            <div class="ibox collapsed">
                                <div class="ibox-title">
                                    <a class="collapse-link"><h5>{{ trans('salon.staff_security') }} <i class="fa fa-chevron-up"></i></h5></a>
                                </div>
                                <div class="ibox-content">
                                    {{ Form::open(array('class' => 'edit-salon')) }}
                                    {{ Form::hidden('user_id', $employee->id, array('id' => 'employeeId')) }}
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email">{{ trans('main.email_address') }}</label>
                                                {{ Form::email('email', null, array('id' => 'email', 'class' => 'form-control', 'placeholder' => $employee->email)) }}
                                                <small class="text-danger">{{ $errors->first('email') }}</small>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="new_password">{{ trans('main.new_password') }}</label>
                                                {{ Form::password('password', array('id' => 'password_new', 'class' => 'form-control', 'placeholder' => 'Password')) }}
                                                <small class="text-danger">{{ $errors->first('password_new') }}</small>
                                            </div>
                                            <div class="form-group">
                                                <label for="new_password_confirm">{{ trans('auth.password_confirmation') }}</label>
                                                {{ Form::password('password_confirmation', array('id' => 'new_password_confirm', 'class' => 'form-control', 'placeholder' => trans('auth.password_confirmation'))) }}
                                                <small class="text-danger">{{ $errors->first('new_password_confirm') }}</small>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-success" onclick="updateStaffSecurity()">{{ trans('salon.update_salon_info') }}</button>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="tab-2" class="tab-pane">
                    <div class="panel-body">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    @if(isset($staff_hours))
                                        <div id="staffScheduleTable" class="table-responsive table-schedule m-t m-b m-l m-r staff-hours-table">
                                            <table class="table table-striped table-bordered table-hover d-table text-center">
                                                <thead>
                                                    <tr>
                                                        <th>{{ trans('salon.working_hours_day') }}</th>
                                                        <th>{{ trans('salon.hours_start_time') }}</th>
                                                        <th>{{ trans('salon.hours_end_time') }}</th>
                                                        <th>{{ trans('salon.hours_lunch_time') }}</th>
                                                        <th>{{ trans('salon.hours_lunch_end') }}</th>
                                                        <th>{{ trans('salon.options') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($staff_hours as $hours)
                                                    <tr class="dates-row" data-date="{{ $hours['date']['date'] }}">
                                                        <td>{{ $hours['date']['dayname'] }}<br>{{ $hours['date']['date'] }}</td>
                                                        <td class="work-start-td">{{ $hours['timetable']['start'] }}</td>
                                                        <td class="work-end-td">{{ $hours['timetable']['end'] }}</td>
                                                        <td class="lunch-start-td">{{ $hours['timetable']['lunch_start'] }}</td>
                                                        <td class="lunch-end-td">{{ $hours['timetable']['lunch_end'] }}</td>
                                                        <td class="user-options">
                                                            <a href="#" data-date="{{ $hours['date']['date'] }}" data-last="{{ $last_date }}" data-toggle="modal" data-target="#editSchedule" class="edit-schedule">
                                                                <i class="fa fa-edit table-edit"></i>
                                                                Edit
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <button type="button" onclick="deleteSchedule({{ $employee->id }})" class="btn btn-danger">{{ trans('salon.deleteSchedule') }}</button>
                                        </div>
                                    @else
                                    <div class="text-center">
                                        <h2>{{ trans('salon.no_schedule_defined') }}</h2>
                                        <div class="additional-action-required">
                                            @if($message != null)
                                            <h3>{{ trans('salon.complete_schedule') }}</h3>
                                                @foreach($message as $date)
                                                <h4 class="text-muted">
                                                    @if($employee->salon->time_format == 'time-ampm')
                                                    {{ \Carbon\Carbon::parse($date)->format('m.d.Y')}}
                                                    @else
                                                    {{ \Carbon\Carbon::parse($date)->format('d.m.Y')}}
                                                    @endif
                                                </h4>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button class="btn btn-default" data-toggle="tab" href="#tab-3"><i class="fa fa-plus"></i> {{ trans('salon.add_work_hours') }}</button>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="tab-3" class="tab-pane">
                    <div class="panel-body">
                        <div class="col-lg-12">
                            <div class="ibox float-e-margins">
                                <div class="ibox-title">
                                    <div class="row wrapper border-bottom white-bg page-heading">
                                        <div class="row booking-steps text-center m-t m-b">
                                            <div class="step-wrap col-xs-4">
                                                <h1 class="booking-step step-1 active">1</h1>
                                                <h2 class="booking-step-info step-1 active">{{ trans('salon.select_start_date') }}</h2>
                                            </div>
                                            <div class="step-wrap col-xs-4">
                                                <h1 class="booking-step step-2 disabled">2</h1>
                                                <h2 class="booking-step-info step-2 disabled">{{ trans('salon.select_shifts') }}</h2>
                                            </div>
                                            <div class="step-wrap col-xs-4">
                                                <h1 class="booking-step step-3 disabled">3</h1>
                                                <h2 class="booking-step-info step-3 disabled">{{ trans('salon.add_hours') }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                   

                                <div class="ibox-content">
                                    <div class="starting-date-wrap active">
                                        @include('partials.schedule.selectStartingDate')
                                    </div>
                                    <div class="shift-select-wrap">
                                        @include('partials.schedule.selectShifts')
                                    </div>
                                    <div class="add-schedule-wrap">
                                        @include('partials.schedule.addSchedule')
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div id="tab-4" class="tab-pane">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-content">
                                        <div class="row">
                                            <div class="col-lg-3"></div>
                                            <div class="col-lg-6">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ trans('salon.service_name') }}</th>
                                                            <th>{{ trans('salon.status') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($services as $service)
                                                        <tr>
                                                            <td>{{ $service->service_details->name }}</td>
                                                            <td><input class="service-selection" type="checkbox" name="{{ $service->id }}" @foreach($service_staff as $service_st) @if($service_st->service_id === $service->id) checked @endif @endforeach></td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                                <button type="button" class="btn btn-default" onclick="updateStaffServices({{ $employee->id }})">{{ trans('salon.update') }}</button>
                                            </div>
                                            <div class="col-lg-3"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.editSchedule')

<script>
    var update_profile_route = '{{ route('updateUserProfile') }}';
    var update_security_route = '{{ route('updateUserSecurity') }}';

    var salon_closed = '{{ trans('salon.salon_closed') }}';
    var week_sch = '{{ trans('salon.week_sch') }}';
    var two_weeks = '{{ trans('salon.two_weeks') }}';
    var three_weeks = '{{ trans('salon.three_weeks') }}';
    var four_weeks = '{{ trans('salon.four_weeks') }}';
    var week_start = {{ $employee->salon->week_starting_on }};
    var week_start_indicator = '{{ trans('salon.week_start_ind') }}';
    var time_format = '{{ $employee->salon->time_format }}';
    var button_finish = '{{ trans('salon.button_finish') }}';
    var button_next = '{{ trans('salon.next') }}';
    var trans_warning = '{{ trans('salon.warning') }}';
    var bookings_found_trans = '{{ trans('salon.bookings_found_trans') }}';
    var reschedule_canceled = '{{ trans('salon.reschedule_canceled') }}';
    var trans_success = '{{ trans('salon.trans_success') }}';
    var delete_warning = '{{ trans('salon.delete_warning') }}';
    var trans_shift = '{{ trans('salon.trans_shift') }}';
    var updated_successfuly = '{{ trans('salon.updated_successfuly') }}';
    var error_updating = '{{ trans('salon.error_updating') }}';
    
    $(document).ready(function(){
        $('.d-table').DataTable({
            pageLength: 7,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            ordering: false,
            buttons: [
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},
                {extend: 'print',
                customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');
                    $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]
        });
    });
    
    if(week_start == 2) {
        week_start = 0;
    } else {
        week_start = 1;
    }
    
    var disabled_days = [];
    for(var i=0; i<7; i++) {
        if(i != week_start) {
            disabled_days.push(i);
        }
    }
    
    $('.pick-birthday').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        startView: 'decades',
    });
    
    $("#selectedDate").datepicker({
        keyboardNavigation: false,
        forceParse: false,
        calendarWeeks: true,
        weekStart: week_start,
        daysOfWeekDisabled: disabled_days,
    });

</script>
@if(session('active_tab'))
<script>
    $(document).ready(function() {
        var id = '{{ session("active_tab") }}';
        $('#tab-1').removeClass('active');
        $('#tab-2').removeClass('active');
        $('#tab-3').removeClass('active');
        $('#tab-1-li').removeClass('active');
        $('#tab-2-li').removeClass('active');
        $('#tab-3-li').removeClass('active');
        $('#tab-'+id).addClass('active');
        $('#tab-'+id+'-li').addClass('active');
        
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });
    });
</script>
@endif
@endsection