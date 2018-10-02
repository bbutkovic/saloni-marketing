<div class="modal fade" id="editSchedule" tabindex="-1" role="dialog" aria-labelledby="newStaffMember" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.edit_schedule') }} - <span class="date"></span></h4>
            </div>
            <div class="modal-body">
                {{ Form::open(array('id' => 'editSchedule')) }}
                <div class="row">
                    <div id="modal-edit-schedule" class="col-lg-12">
                        <div class="row text-center">
                            <div class="input-wrap">
                                <label for="workingStatus">{{ trans('salon.working_status') }}</label>
                                <input type="checkbox" name="working_status" id="workingStatus" class="form-control" checked>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="input-wrap">
                                <label for="endDate">{{ trans('salon.end_date') }}</label>
                                <input name="end_date" id="endDate" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::hidden('uid', $employee->id) }}
                            {{ Form::hidden('time_format', $employee->salon->time_format, array('id' => 'time_format')) }}
                            <table id="edit-schedule-modal" class="table table-working-hours">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{ trans('salon.hours_start_time') }}</th>
                                        <th class="text-center">{{ trans('salon.hours_end_time') }}</th>
                                        <th class="text-center">{{ trans('salon.hours_lunch_time') }}</th>
                                        <th class="text-center">{{ trans('salon.hours_lunch_end') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="schedule">
                                        <input type="hidden" name="date" id="startDate">
                                        <td>
                                            <select name="work_start" class="form-control time-select">
                                                @foreach($time_list['Monday'] as $mtime=>$time)
                                                    <option value="{{$time}}">{{ $time }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="work_end" class="form-control time-select">
                                                @foreach($time_list['Monday'] as $mtime=>$time)
                                                    <option value="{{$time}}">{{ $time }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="lunch_start" class="form-control time-select">
                                                <option value="0">{{ trans('salon.not_defined') }}</option>
                                                @foreach($time_list['Monday'] as $mtime=>$time)
                                                    <option value="{{$time}}">{{ $time }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="lunch_end" class="form-control time-select">
                                                <option value="0">{{ trans('salon.not_defined') }}</option>
                                                @foreach($time_list['Monday'] as $mtime=>$time)
                                                    <option value="{{$time}}">{{ $time }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        </div>
                        
                    </div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="button" class="btn btn-primary" onclick="updateSchedule()">{{ trans('salon.update') }}</button>
            </div>
        </div>
    </div>
</div>