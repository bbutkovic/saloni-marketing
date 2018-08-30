<div class="modal fade" id="newStaffMember" tabindex="-1" role="dialog" aria-labelledby="newStaffMember" aria-hidden="true">
    <div class="modal-dialog">
        <div class="client-loader hidden">
            <h2 class="text-center">{{ trans('salon.please_wait') }}</h2>
            <div class="loader"></div>
        </div>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.add_new_staff') }}</h4>
                <small>{{ trans('salon.user_creation_info') }}</small>
            </div>
            <div class="modal-body">
                {{ Form::open(array('id' => 'newMember')) }}
                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="first_name">{{ trans('auth.first_name') }}</label>
                            {{ Form::text('first_name', null, array('id' => 'first_name', 'class' => 'form-control', 'required')) }}
                        </div>
                        <div class="form-group">
                            <label for="last_name">{{ trans('auth.last_name') }}</label>
                            {{ Form::text('last_name', null, array('id' => 'last_name', 'class' => 'form-control', 'required')) }}
                        </div>
                        <div class="form-group">
                            <label for="email_address">{{ trans('salon.business_email') }}</label>
                            {{ Form::email('email_address', null, array('id' => 'email_address', 'class' => 'form-control', 'required')) }}
                        </div>
                        <div class="form-group">
                            <label for="password">{{ trans('auth.password') }}</label>
                            {{ Form::password('password', array('id' => 'password', 'class' => 'form-control', 'required')) }}
                        </div>
                        <div class="form-group">
                            <label for="locations">{{ trans('salon.add_staff_location') }}</label>
                            <select id="selected_location" class="form-control" name="location">
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user_role">{{ trans('salon.select_user_role') }}</label>
                            <select id="user_role" class="form-control">
                                @foreach($user_roles as $key=>$role)
                                <option value="{{ $key }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2"></div>
                </div>
                {{ Form::close() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="button" class="btn btn-primary" onclick="addStaffMember()">{{ trans('salon.add_staff') }}</button>
            </div>
        </div>
    </div>
</div>