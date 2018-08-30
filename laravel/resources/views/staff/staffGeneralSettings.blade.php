@extends('main')

@section('styles')
@endsection

@section('scripts')
    {{ HTML::script('js/salon/staff.js') }}
@endsection

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 class="section-heading pull-left">{{ trans('salon.staff_general_settings') }}</h2>
    </div>
</div>

<div id="location-options" class="user-settings-wrapper">
    <div class="wrapper wrapper-content">
        {{ Form::open(array('id' => 'edit-salon')) }}
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox-content general-settings text-center">
                    <div id="email_staff_rosters">
                        <label for="available">{{ trans('salon.email_rosters') }}: </label>
                        <div class="radio radio-info radio-inline">
                            <input type="radio" id="emailRosters1" value="1" name="email_rosters" @if($salon->salon_extras->email_staff_rosters == 1) ? checked : null @endif>
                            <label for="emailRosters1">{{ trans('salon.radio_yes') }}</label>
                        </div>
                        <div class="radio radio-inline">
                            <input type="radio" id="emailRosters2" value="0" name="email_rosters" @if($salon->salon_extras->email_staff_rosters != 1) ? checked : null @endif>
                            <label for="emailRosters2">{{ trans('salon.radio_no') }}</label>
                        </div>
                    </div>
                    <div id="emailDay">
                        <label for="weekday">{{ trans('salon.email_day') }}: </label>
                        <select id="weekday" class="form-control" name="weekday">
                            @foreach($week as $key=>$weekday)
                            <option value="{{ $key }}" @if($salon->salon_extras->email_day == $key) ? selected : null @endif>{{ $weekday['en'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="emailTime" class="p-xs">
                        <label for="available">{{ trans('salon.email_time') }}: </label>
                        <select id="emailTimeSelect" class="form-control" name="time">
                            @foreach($hours as $key=>$hour)
                            <option value="{{ $key }}" @if($salon->salon_extras->email_time == $key) ? selected : null @endif>{{ $hour }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row text-center">
                        <button type="button" class="btn btn-success m-t" onclick="updateStaffSettings()">{{ trans('salon.update_salon_info') }}</button>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>

<script>
    var settings_route = '{{ route('updateGeneralStaffSettings') }}';
</script>

@endsection