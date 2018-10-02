<h4 class="shift-indicator text-center"></h4>

<div class="schedule-options text-center">
    <select id="repeatFor" name="repeat_for" class="form-control select-hours-type m-r">
        <option value="0" default selected>{{ trans('salon.sch_forever') }}</option>
        @for($i = 1; $i < 52; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>
    <button type="button" class="btn btn-default m-r" onclick="copyLocationHours({{ $employee->location_id }})">{{ trans('salon.copy_location_hours') }}</button>
</div>

<div class="row">
    <div class="time-picker">
        <div class="client-loader hidden">
            <h2 class="text-center">{{ trans('salon.please_wait') }}</h2>
            <div class="loader"></div>
        </div>
        {{ Form::open(array('route' => 'setStaffHours', 'method' => 'post', 'id' => 'update-hours', 'class' => 'm-t')) }}
        {{ Form::hidden('repeatFor', null, array('id' => 'repeatForInput')) }}
        <table class="table table-working-hours">
            <thead>
                <tr>
                    <th class="text-center">{{ trans('salon.working_hours_day') }}</th>
                    <th class="text-center">{{ trans('salon.staff_working') }}</th>
                    <th class="text-center">{{ trans('salon.hours_start_time') }}</th>
                    <th class="text-center">{{ trans('salon.hours_end_time') }}</th>
                    <th class="text-center">{{ trans('salon.hours_lunch_time') }}</th>
                    <th class="text-center">{{ trans('salon.hours_lunch_end') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($week as $key=>$weekday)
                <tr class="schedule-weekly day-class">
                    <input type="hidden" name="type" class="schedule-type">
                    <td>{{ $weekday['name'] }}</td>
                    <td class="text-center">
                        <div class="checkbox checkbox-primary">
                            <input class="input-schedule working-class" type="checkbox" id="checkbox{{ $weekday['en'] }}" name="select_{{ $weekday['en'] }}">
                            <label for="checkbox{{ $weekday['en'] }}"></label>
                        </div>
                    </td>
                    <td>
                        <select id="work_start_{{$weekday['en']}}" name="work_start_{{$key}}" class="form-control time-select input-schedule work-start-class" required>
                            @foreach($time_list[$weekday['en']] as $mtime=>$time)
                                <option value="{{$time}}">{{ $time }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select id="work_end_{{$weekday['en']}}" name="work_end_{{$key}}" class="form-control time-select input-schedule work-end-class" required>
                            @foreach($time_list[$weekday['en']] as $mtime=>$time)
                                <option value="{{$time}}">{{ $time }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select id="lunch_start_{{$weekday['en']}}" data-weekday="{{$weekday['en']}}" name="lunch_start_{{$key}}" class="form-control alt-select time-select input-schedule lunch-start-class" required>
                            <option value="0" selected default>{{ trans('salon.not_defined') }}</option>
                            <option value="12:00">12:00</option>
                            @foreach($time_list[$weekday['en']] as $mtime=>$time)
                                <option value="{{$time}}">{{ $time }}</option>
                            @endforeach
                        </select>
                    <td>
                        <select id="lunch_end_{{$weekday['en']}}" name="lunch_end_{{$key}}" class="form-control alt-select time-select input-schedule lunch-end-class" required>
                            <option value="0" selected default>{{ trans('salon.not_defined') }}</option>
                            <option value="12:45">12:45</option>
                            @foreach($time_list[$weekday['en']] as $mtime=>$time)
                                <option value="{{$time}}">{{ $time }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="update-timetable text-center">
            <div class="row">
                <div class="col-lg-12">
                    <button type="button" class="btn btn-danger m-r" onclick="clearHours()">{{ trans('salon.clear_hours') }}</button>
                    <button type="button" class="btn btn-success submit-schedule" data-repeats="0"></button>
                </div>
            </div>
        </div>
        </div>
        {{ Form::close() }}
    </div>
</div>