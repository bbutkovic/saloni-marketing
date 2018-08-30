@if(isset($website_content) && $website_content->website_booking_text != null)
    <div class="row m-b">
        <h3 class="text-muted">{{ $website_content->website_booking_text }}</h3>
    </div>
@endif

@if(!Auth::user())
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
    <div class="row">
        @if(!isset($category_list))
            <select id="clientSelectLocation" class="m-b m-r btn btn-default @if(count($location_list) < 2) hidden @endif" onchange="clientSelectLocation()">
                <option value="default" selected disabled>{{ trans('salon.select_location') }}</option>
                @foreach($location_list as $location)
                    <option value="{{ $location->id }}">{{ $location->location_name }}</option>
                @endforeach
            </select>
        @endif
        <select id="selectCategory" class="m-b m-l btn btn-default client-select-category @if(!isset($category_list) || isset($admin_booking)) hidden @endif" onchange="selectCategory()">
            <option value="default" selected disabled>{{ trans('salon.select_category_s') }}</option>
        </select>
    </div>
    <hr>
    <div class="row">
        @if(Auth::user())
        <div class="client-loyalty-container col-md-6 hidden">
            <h4 class="text-left">{{ trans('salon.loyalty_points') }}: <strong id="clientLoyaltyPoints"></strong></h4>
            <h4 class="text-left">{{ trans('salon.loyalty_promo') }}: <span class="loyalty-promo-text text-muted"></span></h4>
            <ul class="loyalty-free-groups text-left"></ul>
            <select name="free_service" id="freeServices" class="form-control hidden">
                <option value="0" selected disabled>{{ trans('salon.select_service') }}</option>
            </select>
            <select id="discountList" class="form-control hidden">
                <option value="0" selected disabled>{{ trans('salon.select_discount') }}</option>
            </select>
            <h4 class="text-left">{{ trans('salon.points_needed') }}: <strong id="clientPointsNeeded"></strong></h4>
        </div>
        @endif
        <div class="client-services-container @if(Auth::user()) col-md-6 @endif">
            {{ Form::open(array('route' => 'submitSelectedServices', 'id' => 'submitServices', 'class' => 'm-b')) }}
            <div id="serviceList"></div>
            <h2 class="text-center hidden" id="totalPriceCalc">{{ trans('salon.total_price') }} <span id="basePriceStatus"></span></h2>
            {{ Form::close() }}
        </div>
    </div>
</div>
