<div class="modal fade" id="addNewClientModal" tabindex="-1" role="dialog" aria-labelledby="addNewClientModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('salon.add_new_client') }}</h4>
            </div>
            {{ Form::open(array('route' => 'addNewClientInfo', 'method' => 'post')) }}
                {{ Form::hidden('location_id', null, array('id' => 'locationId')) }}
                <div class="modal-body">
                    <div class="row">
                        @if($salon->client_fields->first_name === 1)
                        <div class="col-lg-6 form-group">
                            <label for="first_name">{{ trans('salon.first_name') }}</label>
                            {{ Form::text('first_name', null, array('id' => 'firstName', 'class' => 'form-control')) }}
                        </div>
                        @endif
                        @if($salon->client_fields->last_name === 1)
                        <div class="col-lg-6 form-group">
                            <label for="last_name">{{ trans('salon.last_name') }}</label>
                            {{ Form::text('last_name', null, array('id' => 'lastName', 'class' => 'form-control')) }}
                        </div>
                        @endif
                        @if($salon->client_fields->phone === 1)
                        <div class="col-lg-6 form-group">
                            <label for="phone">{{ trans('salon.phone') }}</label>
                            {{ Form::text('phone', null, array('id' => 'phone', 'class' => 'form-control')) }}
                        </div>
                        @endif
                        <div class="col-lg-6 form-group">
                            <label for="email">{{ trans('salon.email') }}</label>
                            {{ Form::text('email', null, array('id' => 'email', 'class' => 'form-control', 'required')) }}
                        </div>
                        @if($salon->client_fields->address === 1)
                        <div class="col-lg-6 form-group">
                            <label for="address">{{ trans('salon.address') }}</label>
                            {{ Form::text('address', null, array('id' => 'address', 'class' => 'form-control')) }}
                        </div>
                        @endif
                        @if($salon->client_fields->gender === 1)
                        <div class="col-lg-6 form-group">
                            <label for="gender">{{ trans('salon.gender') }}</label>
                            <select name="gender" class="form-control">
                                <option value="0" selected disabled>{{ trans('salon.gender') }}</option>
                                <option value="1">{{ trans('salon.male') }}</option>
                                <option value="2">{{ trans('salon.female') }}</option>
                            </select>
                        </div>
                        @endif
                        @foreach($booking_fields as $field)
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="{{ $field->field_name }}">{{ $field->field_title }}</label>
                                @if($field->field_type === '1')
                                <input type="text" name="{{ $field->field_name }}" id="{{ $field->field_name }}" class="form-control">
                                @else
                                <select name="{{ $field->field_name }}" id="{{ $field->field_name }}" class="form-control">
                                    @foreach($field->select_options as $option)
                                    <option value="{{ $option->option_value }}">{{ $option->option_name }}</option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        <div class="client-account-create row m-t">
                            <div class="col-lg-12 form-group m-l">
                                <label for="assignUserAccount">{{ trans('salon.create_user_account_prompt') }} <small class="text-muted">({{ trans('salon.create_user_account_prompt_desc') }})</small></label>
                                <div class="radio radio-info radio-inline">
                                    <input type="radio" value="1" name="create_account" id="createAccount">
                                    <label for="createAccount">{{ trans('salon.radio_yes') }}</label>
                                </div>
                                <div class="radio radio-inline">
                                    <input type="radio" value="0" name="create_account" id="skipAccountCreation" checked>
                                    <label for="skipAccountCreation">{{ trans('salon.radio_no') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                    <button type="button" class="btn btn-success" onclick="submitNewClient()">{{ trans('salon.submit') }}</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
