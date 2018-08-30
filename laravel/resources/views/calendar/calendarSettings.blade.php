@extends('main')


@section('styles')
    {{ HTML::style('css/plugins/spectrum/spectrum.css') }}
@endsection

@section('scripts')
    {{ HTML::script('js/calendar.js') }}
    {{ HTML::script('js/plugins/spectrum/spectrum.js') }}
@endsection

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 class="section-heading pull-left calendar-title"><i class="fa fa-calendar"></i> {{ trans('salon.calendar_management') }}</h2>
        <a href="{{ route('appointments') }}" class="btn btn-default new-location-btn"><i class="fa fa-eye"></i> {{ trans('salon.view_calendar') }}</a>
    </div>
</div>

<div class="wrapper wrapper-content">
    <div class="tabs-container">
        
        <ul class="nav nav-tabs">
            <li id="tab-1-li" class="active"><a data-toggle="tab" href="#tab-1">{{ trans('salon.settings') }}</a></li>
            <li id="tab-2-li" class=""><a data-toggle="tab" href="#tab-2">{{ trans('salon.calendar_colors') }}</a></li>
        </ul>
        
        <div class="tab-content">
            
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    {{ Form::open(array('id' => 'updateCalendar')) }}
                    <h4 class="text-center booking-fields">{{ trans('salon.main_calendar_settings') }}</h4>
                    <div class="row text-center select-options">
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="appointmentInterval">{{ trans('salon.appointment_interval') }}</label>
                                {{ Form::select('appointment_interval', ['00:05:00'=>'5 min', '00:10:00'=>'10 min', '00:15:00'=>'15 min', '00:20:00'=>'20 min', '00:25:00'=>'25 min', '00:30:00'=>'30 min', '00:35:00'=>'35 min', '00:40:00'=>'40 min', '00:45:00'=>'45 min', '00:50:00'=>'50 min', '00:55:00'=>'55 min', '01:00:00'=>'60 min'], isset($calendar_options) ? $calendar_options->appointment_interval : null, array('class' => 'form-control','id' => 'appointmentInterval', 'required')) }}
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="defaultTab">{{ trans('salon.default_tab') }}</label>
                                {{ Form::select('default_tab', ['month' => trans('salon.tab_month'), 'agendaWeek' => trans('salon.tab_week'), 'agendaDay' => trans('salon.tab_day')], isset($calendar_options) ? $calendar_options->default_tab : null, array('class' => 'form-control', 'id' => 'defaultTab', 'required')) }}
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="appointmentColors">{{ trans('salon.appointment_colors') }}</label>
                                {{ Form::select('appointment_colors', ['status' => trans('salon.status'), 'category' => trans('salon.category')], isset($calendar_options) ? $calendar_options->appointment_colors : null, array('class' => 'form-control', 'id' => 'appointmentColors', 'required')) }}
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="form-group">
                                <label for="appointmentNumber">{{ trans('salon.appointment_number') }}</label>
                                {{ Form::select('appointment_number', [2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 'all' => trans('salon.all')], isset($calendar_options) ? $calendar_options->appointment_number : null, array('class' => 'form-control', 'id' => 'appointmentNumber', 'data-toggle'=>'tooltip', 'data-placement'=>'top', 'data-original-title'=> trans('salon.appointment_number_desc'), 'required' )) }}
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row radio-options">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="services-label" for="staff_photo">{{ trans('salon.display_staff_photo') }}</label>
                                <div class="radio radio-info radio-inline">
                                    <input type="radio" id="staffPhoto1" name="staff_photo" @if(isset($calendar_options) && $calendar_options->staff_photo == 1) ? checked : null @endif>
                                    <label for="staffPhoto1">{{ trans('salon.radio_yes') }}</label>
                                </div>
                                <div class="radio radio-inline">
                                    <input type="radio" id="staffPhoto2" name="staff_photo" @if(!isset($calendar_options) || isset($calendar_options) && $calendar_options->staff_photo != 1) ? checked : null @endif>
                                    <label for="staffPhoto2">{{ trans('salon.radio_no') }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="services-label" for="drag_and_drop">{{ trans('salon.drag_and_drop') }}</label>
                                <div class="radio radio-info radio-inline">
                                    <input type="radio" id="dragAndDrop1" name="drag_and_drop" @if(!isset($calendar_options) || isset($calendar_options) && $calendar_options->drag_and_drop == 0) ? checked : null @endif>
                                    <label for="dragAndDrop1">{{ trans('salon.radio_yes') }}</label>
                                </div>
                                <div class="radio radio-inline">
                                    <input type="radio" id="dragAndDrop2" name="drag_and_drop" @if(isset($calendar_options) && $calendar_options->drag_and_drop == 1) ? checked : null @endif>
                                    <label for="dragAndDrop2">{{ trans('salon.radio_no') }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="services-label" for="waiting_list">{{ trans('salon.waiting_list') }}</label>
                                <div class="radio radio-info radio-inline">
                                    <input type="radio" id="waitingList1" name="waiting_list" @if(isset($calendar_options) && $calendar_options->waiting_list == 1) ? checked : null @endif>
                                    <label for="waitingList1">{{ trans('salon.radio_yes') }}</label>
                                </div>
                                <div class="radio radio-inline">
                                    <input type="radio" id="waitingList2" name="waiting_list" @if(!isset($calendar_options) || isset($calendar_options) && $calendar_options->waiting_list == 0) ? checked : null @endif>
                                    <label for="waitingList2">{{ trans('salon.radio_no') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h4 class="text-center booking-fields">{{ trans('salon.field_list') }}</h4>
                    <div class="row text-center update-calendar">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="clientNotes">{{ trans('salon.client_notes') }}</label>
                                <input value="1" id="clientNotes" class="checkbox" type="checkbox" name="client_notes" @if(isset($calendar_settings) && $calendar_settings->client_notes == 1)) ? checked : null @endif>
                            </div>
                            <div class="form-group">
                                <label for="phoneNumber">{{ trans('salon.phone_number') }}</label>
                                <input value="1" id="phoneNumber" class="checkbox" type="checkbox" name="phone_number" @if(isset($calendar_settings) && $calendar_settings->phone_number == 1)) ? checked : null @endif>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="email">{{ trans('salon.email') }}</label>
                                <input value="1" id="email" class="checkbox" type="checkbox" name="email" @if(isset($calendar_settings) && $calendar_settings->email_address == 1)) ? checked : null @endif>
                            </div>
                            <div class="form-group">
                                <label for="address">{{ trans('salon.client_address') }}</label>
                                <input value="1" id="address" class="checkbox" type="checkbox" name="address" @if(isset($calendar_settings) && $calendar_settings->address == 1)) ? checked : null @endif>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="newClientIndicator">{{ trans('salon.new_client_indicator') }}</label>
                                <input value="1" id="newClientIndicator" class="checkbox" type="checkbox" name="new_client_indicator" @if(isset($calendar_settings) && $calendar_settings->new_client_indicator == 1)) ? checked : null @endif>
                            </div>
                            <div class="form-group">
                                <label for="referrer">{{ trans('salon.referrer') }}</label>
                                <input value="1" id="referrer" class="checkbox" type="checkbox" name="referrer" @if(isset($calendar_settings) && $calendar_settings->referrer == 1)) ? checked : null @endif>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row text-center">
                        <button class="btn btn-success m-t" type="submit">{{ trans('salon.update') }}</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
            
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                    {{ Form::open(array('id' => 'updateCalendarColors')) }}
                    <small class="text-muted">{{ trans('salon.colors_desc') }}</small>
                    <div class="row text-center calendar-colors">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="spectrumBooked">{{ trans('salon.status_booked') }}</label>
                                <input type="text" value="{{ $colors->status_booked }}" id="spectrumBooked" name="status_booked">
                            </div>
                            <div class="form-group">
                                <label for="spectrumConfirmed">{{ trans('salon.status_confirmed') }}</label>
                                <input type="text" value="{{ $colors->status_confirmed }}" id="spectrumConfirmed" name="status_confirmed">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="spectrumComplete">{{ trans('salon.status_complete') }}</label>
                                <input type="text" value="{{ $colors->status_complete }}" id="spectrumComplete" name="status_complete">
                            </div>
                            <div class="form-group">
                                <label for="spectrumCancelled">{{ trans('salon.status_cancelled') }}</label>
                                <input type="text" value="{{ $colors->status_cancelled }}" id="spectrumCancelled" name="status_cancelled">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="spectrumOnline">{{ trans('salon.status_waiting_list') }}</label>
                                <input type="text" value="{{ $colors->status_waiting_list }}" id="spectrumOnline" name="status_waiting_list">
                            </div>
                            <div class="form-group">
                                <label for="spectrumRebokeed">{{ trans('salon.status_rebooked') }}</label>
                                <input type="text" value="{{ $colors->status_rebooked }}" id="spectrumRebokeed" name="status_rebooked">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="spectrumArrived">{{ trans('salon.status_arrived') }}</label>
                                <input type="text" value="{{ $colors->status_arrived }}" id="spectrumArrived" name="status_arrived">
                            </div>
                            <div class="form-group">
                                <label for="spectrumNoShow">{{ trans('salon.status_noshow') }}</label>
                                <input type="text" value="{{ $colors->status_noshow }}" id="spectrumNoShow" name="status_noshow">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label for="spectrumPaid">{{ trans('salon.status_paid') }}</label>
                                <input type="text" value="{{ $colors->status_arrived }}" id="spectrumPaid" name="status_paid">
                            </div>
                        </div>
                    </div>
                    <div class="row text-center">
                        <button class="btn btn-success m-t" type="submit">{{ trans('salon.update') }}</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
            
        </div>
        
    </div>
</div>

<script>

    var update_settings_route = '{{ route('updateCalendar') }}';
    var update_colors_route = '{{ route('updateCalendarColors') }}';

    $("#spectrumBooked").spectrum({
        color: "{{ $colors->status_booked }}",
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
        change: function(color) {
            $("#spectrumBooked").val(color.toHexString());
        }
    });
    
    $("#spectrumConfirmed").spectrum({
        color: "{{ $colors->status_confirmed }}",
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
        change: function(color) {
            $("#spectrumConfirmed").val(color.toHexString());
        }
    });
    
    $("#spectrumComplete").spectrum({
        color: "{{ $colors->status_complete }}",
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
        change: function(color) {
            $("#spectrumComplete").val(color.toHexString());
        }
    });

    $("#spectrumPaid").spectrum({
        color: "{{ $colors->status_paid }}",
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
        change: function(color) {
            $("#spectrumComplete").val(color.toHexString());
        }
    });
    
    $("#spectrumCancelled").spectrum({
        color: "{{ $colors->status_cancelled }}",
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
        change: function(color) {
            $("#spectrumCancelled").val(color.toHexString());
        }
    });
    
    $("#spectrumOnline").spectrum({
        color: "{{ $colors->status_online }}",
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
        change: function(color) {
            $("#spectrumOnline").val(color.toHexString());
        }
    });
    
    $("#spectrumRebokeed").spectrum({
        color: "{{ $colors->status_rebooked }}",
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
        change: function(color) {
            $("#spectrumRebokeed").val(color.toHexString());
        }
    });
    
    $("#spectrumArrived").spectrum({
        color: "{{ $colors->status_arrived }}",
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
        change: function(color) {
            $("#spectrumArrived").val(color.toHexString());
        }
    });
    
    $("#spectrumNoShow").spectrum({
        color: "{{ $colors->status_noshow }}",
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
        change: function(color) {
            $("#spectrumNoShow").val(color.toHexString());
        }
    });
    
</script>


@endsection