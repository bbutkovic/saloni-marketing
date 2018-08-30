@if(isset($website_content) && $website_content->website_booking_text != null)
    <div class="row">
        <h3 class="text-muted m-b-lg">{{ $website_content->website_booking_text }}</h3>
    </div>
@endif
@if(isset($category_list) && isset($admin_booking))
    <input type="hidden" name="loyalty_type" @if(isset($admin_location->loyalty_program)) value="{{ $admin_location->loyalty_program->loyalty_type }}" @endif id="loyaltyProgramType">
    <h3 class="text-muted">{{ trans('salon.select_services') }}</h3>

    <select id="selectCategory" class="m-b btn btn-default active" onchange="selectCategory()">
        <option value="default" selected disabled>{{ trans('salon.select_category_s') }}</option>
        @foreach($category_list as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
@endif
@if(!Auth::user() && !isset($admin_booking))
    <div id="userAccountOptions">
        <div class="select-section">
            <h4>{{ trans('salon.continue_guest_registered') }}</h4>
            <small class="text-muted">{{ trans('salon.continue_guest_registered_desc') }}</small>
            <div class="login-register-row">
                <button type="button" class="register-button" onclick="clientRegister()"><i class="fa fa-user"></i> {{ trans('auth.register') }}</button>
                <button type="button" class="login-button" onclick="clientLogin()"><i class="fa fa-sign-in"></i> {{ trans('auth.login') }}</button>
                <button type="button" class="guest-button" onclick="continueAsGuest()">{{ trans('salon.continue_as_guest') }}</button>
            </div>
        </div>
        <form class="form-section"></form>
    </div>
@endif
<div class="bookingStepLocation @if(!Auth::user()) hidden @endif">
    <div class="client-loyalty-container col-md-6">
        @if(isset($category_list) && Auth::user() && isset($client_loyalty) && $client_loyalty != null)
            <h4 class="text-muted text-center">{{ $client_loyalty['message'] }}</h4>
            @if($client_loyalty['type'] === 3 && $client_loyalty['status'] === 1)
                <h5 class="text-center m-b-lg">{{ $client_loyalty['max_discount_trans'] }}<br>{{ $client_loyalty['next_discount'] }}</h5>
            @endif
        @endif
    </div>
    <div class="client-services-container col-md-6">
        @if(!isset($category_list))
            <select id="clientSelectLocation" class="m-b m-r btn btn-default" onchange="clientSelectLocation()">
                <option value="default" selected disabled>{{ trans('salon.select_location') }}</option>
                @foreach($location_list as $location)
                    <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                @endforeach
            </select>
        @endif
        <select id="selectCategory" class="m-b m-l btn btn-default client-select-category @if(!isset($category_list) || isset($admin_booking)) hidden @endif" onchange="selectCategory()">
            <option value="default" selected disabled>{{ trans('salon.select_category_s') }}</option>
            @if(isset($category_list))
                @foreach($category_list as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
{{ Form::open(array('route' => 'submitSelectedServices', 'id' => 'submitServices', 'class' => 'm-b')) }}
<div id="serviceList">

</div>
<input type="hidden" name="loyalty_type" @if(isset($client_loyalty)) value="{{ $client_loyalty['type'] }}" @endif id="loyaltyProgramType">
<input type="hidden" name="loyalty_status" @if(isset($client_loyalty)) value="{{ $client_loyalty['status'] }}" @endif id="loyaltyStatus">
<input type="hidden" name="loyalty_max_amount" @if(isset($loyalty_program)) value="{{ $loyalty_program['max_amount'] }}" @endif id="loyaltyMaxAmount">
<input type="hidden" name="loyalty_free_service" @if(isset($loyalty_program)) value="{{ $loyalty_program['free_service'] }}" @endif id="loyaltyFreeService">
<input type="hidden" name="loyalty_discount" @if(isset($client_loyalty) && $client_loyalty['type'] === 3 && $client_loyalty['status'] === 1) value="{{$client_loyalty['max_discount']}}" @endif id="loyaltyDiscount">
<input type="hidden" name="totalPriceCalculated" id="totalPriceCalculated">
<h2 class="text-center hidden" id="totalPriceCalc">{{ trans('salon.total_price') }} <span id="basePriceStatus"></span></h2>
{{ Form::close() }}
