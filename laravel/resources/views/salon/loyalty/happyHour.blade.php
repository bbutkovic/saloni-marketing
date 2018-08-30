@extends('main')

@section('styles')
    {{ HTML::style('css/plugins/datepicker/datepicker.css') }}
    {{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
@endsection

@section('scripts')
    {{ HTML::script('js/loyalty/loyalty.js') }}
    {{ HTML::script('js/plugins/jasny/jasny-bootstrap.min.js') }}
    {{ HTML::script('js/plugins/datepicker/datepicker.js') }}
@endsection

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading">{{ trans('salon.loyalty_management') }}</h2>
        </div>
    </div>

    <div id="location-options" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="ibox-content m-b">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="happy_hour_active" class="happy_hour_label">{{ trans('salon.happy_hour_active') }}</label>
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="active-1" value="1" name="happy_hour_active" @if($location->happy_hour === 1) checked @endif>
                                <label for="active-1">{{ trans('salon.radio_yes') }}</label>
                            </div>
                            <div class="radio radio-inline">
                                <input type="radio" id="active-2" value="0" name="happy_hour_active" @if($location->happy_hour === 0) checked @endif>
                                <label for="active-2">{{ trans('salon.radio_no') }}</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <h5 class="text-muted">{{ trans('salon.choose_happy_hour_days') }}</h5>
                        {{ Form::open(array('route' => 'updateHappyHourSettings', 'id' => 'happyHourPromotion')) }}
                        <table class="table table-working-hours">
                            <thead>
                            <tr>
                                <th class="text-center">{{ trans('salon.working_hours_day') }}</th>
                                <th class="text-center">{{ trans('salon.active') }}</th>
                                <th class="text-center">{{ trans('salon.start') }}</th>
                                <th class="text-center">{{ trans('salon.end') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $counter = 0; ?>
                            @foreach($week as $key=>$weekday)
                                <tr class="schedule-weekly day-class">
                                    <td class="text-center" class="input-schedule">{{ $weekday['name'] }}</td>
                                    <td class="text-center">
                                        <div class="checkbox checkbox-primary">
                                            <input class="input-schedule working-class" type="checkbox" id="checkbox{{ $weekday['en'] }}" name="select_{{ $weekday['en'] }}" @if(isset($location->happy_hour_location[$counter]) && $location->happy_hour_location[$counter]->status === 1) checked @endif>
                                            <label for="checkbox{{ $weekday['en'] }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <select id="starting_time_{{$weekday['en']}}" name="starting_time_{{$key}}" class="form-control time-select input-schedule work-start-class" required>
                                            @foreach($time_list as $mtime=>$time)
                                                <option value="{{$mtime}}" @if(isset($location->happy_hour_location[$counter]) && $location->happy_hour_location[$counter]->start === $mtime) selected @endif>{{ $time }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select id="end_time_{{$weekday['en']}}" name="end_time_{{$key}}" class="form-control time-select input-schedule work-end-class" required>
                                            @foreach($time_list as $mtime=>$time)
                                                <option value="{{$mtime}}" @if(isset($location->happy_hour_location[$counter]) && $location->happy_hour_location[$counter]->end === $mtime) selected @endif>{{ $time }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <?php $counter++; ?>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="row m-t m-l">
                            <div class="form-group">
                                <div class="col-lg-4">
                                    <label for="happyHourDiscount">{{ trans('salon.discount_happy_hour') }}</label>
                                    <input type="text" name="discount" class="form-control" @if($location->happy_hour_discount != null) value="{{ $location->happy_hour_discount }}" @endif required>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center m-t">
                            <button class="btn btn-success">{{ trans('salon.submit') }}</button>
                        </div>
                        {{ Form::close() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('partials.slowDayHour')
    <script>
        var discount_percentage = '{{ trans('salon.discount_percentage') }}';
        var number_of_pts = '{{ trans('salon.number_of_pts') }}';
        var discount_format_error = '{{ trans('salon.discount_format_error') }}';
        var delete_check = '{{ trans('salon.are_you_sure') }}';
        var accept_delete = '{{ trans('salon.accept_delete') }}';
        var cancel = '{{ trans('salon.cancel') }}';
    </script>
@endsection