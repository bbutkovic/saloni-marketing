
{{ Form::open(array('id' => 'clientInsertData', 'class' => 'booking-confirm')) }}
    {{ Form::hidden('booking_location', null, array('id' => 'bookingLocationClient')) }}
    {{ Form::hidden('booking_date', null, array('id' => 'bookingDateClient')) }}
    {{ Form::hidden('booking_from', null, array('id' => 'bookingFromClient')) }}
    {{ Form::hidden('booking_to', null, array('id' => 'bookingToClient')) }}
    {{ Form::hidden('total_price', null, array('id' => 'totalPriceClient')) }}
    {{ Form::hidden('points_awarded', null, array('id' => 'awardedPointsClient')) }}
    {{ Form::hidden('account', 0, array('id' => 'clientAccount')) }}
    {{ Form::hidden('service[]', null, array('id' => 'bookingServiceClient')) }}
    {{ Form::hidden('staff[]', null, array('id' => 'bookingStaffClient')) }}

    <div class="row">
        <small class="text-muted">{{ trans('salon.client_required_data') }}</small>
    </div>
    <div id="clientRequiredFields" class="row">
        @if(!Auth::user())
            @if($salon->client_fields->first_name === 1)
            <div class="col-sm-6 form-group">
                <label for="first_name">{{ trans('salon.first_name') }}</label>
                {{ Form::text('first_name', null, array('id' => 'firstName', 'class' => 'form-control', 'required')) }}
            </div>
            @endif
            @if($salon->client_fields->last_name === 1)
            <div class="col-sm-6 form-group">
                <label for="last_name">{{ trans('salon.last_name') }}</label>
                {{ Form::text('last_name', null, array('id' => 'lastName', 'class' => 'form-control', 'required')) }}
            </div>
            @endif
            <div class="col-sm-6 form-group">
                <label for="email">{{ trans('salon.email') }}</label>
                {{ Form::email('email', null, array('id' => 'email', 'class' => 'form-control', 'required')) }}
            </div>
        @endif

        @if(($salon->client_fields->phone === 1 && !Auth::user()) || ($salon->client_fields->phone && Auth::user() && Auth::user()->user_extras->phone_number === null))
        <div class="col-sm-6 form-group">
            <label for="phone">{{ trans('salon.phone') }}</label>
            {{ Form::text('phone', null, array('id' => 'phone', 'class' => 'form-control', 'required')) }}
        </div>
        @endif
        
        @if(($salon->client_fields->address === 1 && !Auth::user()) || ($salon->client_fields->address && Auth::user() && Auth::user()->user_extras->address === null))
        <div class="col-sm-6 form-group">
            <label for="address">{{ trans('salon.address') }}</label>
            {{ Form::text('address', null, array('id' => 'address', 'class' => 'form-control', 'required')) }}
        </div>
        @endif

        @if(($salon->client_fields->gender === 1 && !Auth::user()) || ($salon->client_fields->gender && Auth::user() && Auth::user()->user_extras->gender === null))
        <div class="col-sm-6 form-group">
            <label for="gender">{{ trans('salon.gender') }}</label>
            <select name="gender" class="form-control" id="gender" required>
                <option value="0" selected disabled>{{ trans('salon.gender') }}</option>
                <option value="1">{{ trans('salon.male') }}</option>
                <option value="2">{{ trans('salon.female') }}</option>
            </select>
        </div>
        @endif
        <br>
    </div>
    
    <div id="bookingRequiredFields" class="row"></div>
    <div id="clientAccountFields" class="row"></div>

    <div class="row booking-account-buttons m-t-lg">
        <div class="row action-buttons-wrap">
            <button type="button" class="btn btn-danger" onclick="returnToPreviousTab(3)"><i class="fa fa-arrow-left"></i> {{ trans('salon.back') }}</button>
            <button class="btn btn-success submit-booking-btn m-t">{{ trans('salon.submit') }}</button>
        </div>
    </div>
{{ Form::close() }}