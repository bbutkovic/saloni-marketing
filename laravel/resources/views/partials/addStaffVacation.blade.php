<div class="modal fade" id="addStaffVacation" tabindex="-1" role="dialog" aria-labelledby="addStaffVacation" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.add_vacation') }}</h4>
            </div>
            {{ Form::open(array('route' => 'addStaffVacation', 'id' => 'editSchedule')) }}
            <div class="modal-body">
                
                <div class="row">

                    <div class="col-lg-12">
                        <h3 class="text-center date m-t m-b"></h3>
                        <div class="form-group">
                            
                            <table id="edit-schedule-modal" class="table table-working-hours">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{ trans('salon.staff_member') }}</th>
                                        <th class="text-center">{{ trans('salon.vacation_start') }}</th>
                                        <th class="text-center">{{ trans('salon.vacation_end') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="schedule">
                                        <td>
                                            <select id="select_staff" class="form-control" name="select_staff">
                                                @foreach($user_list as $user)
                                                <option value="{{$user->id}}">{{ $user->user_extras->first_name }} {{ $user->user_extras->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control vacation-picker" name="vacation_start"></td>
                                        <td><input type="text" class="form-control vacation-picker" name="vacation_end"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group table-working-hours m-t">
                                <div class="add-note">
                                    <label for="note">{{ trans('salon.note') }}</label>
                                    {{ Form::text('note', null, array('id' => 'note', 'class' => 'form-control')) }}
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="submit" class="btn btn-primary">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>