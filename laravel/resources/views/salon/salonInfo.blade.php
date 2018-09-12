@extends('main')

@section('scripts')
    {{ HTML::script('js/salon/salonInfo.js') }}
    {{ HTML::script('js/payments/integrations.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading text-center">{{ trans('salon.main_salon_info') }}</h2>
        </div>
    </div>

    <div id="location-options" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li id="tab-1-li" class="active"><a data-toggle="tab" href="#tab-1">{{ trans('salon.info') }}</a></li>
                    <li id="tab-2-li" class=""><a data-toggle="tab" href="#tab-2">{{ trans('salon.billing_info') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            {{ Form::open(array('id' => 'edit-salon', 'files' => 'true')) }}
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="businessName">{{ trans('salon.business_name') }}*</label>
                                                    {{ Form::text('business_name', $salon->business_name, array('id' => 'businessName', 'class' => 'form-control', 'required')) }}
                                                </div>
                                                <div class="form-group">
                                                    <label for="business_contactname">{{ trans('salon.contactname') }}*</label>
                                                    {{ Form::text('business_contactname', isset($salon->contact_name) ? $salon->contact_name : null, array('id' => 'businessContactName', 'class' => 'form-control', 'required')) }}
                                                </div>
                                                <div class="form-group">
                                                    <label for="emailAddress">{{ trans('salon.business_email') }}</label>
                                                    {{ Form::text('business_email', $salon->email_address, array('id' => 'emailAddress', 'class' => 'form-control')) }}
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="businessPhone">{{ trans('salon.location_phone') }}</label>
                                                    {{ Form::text('business_phone', $salon->business_phone, array('id' => 'businessPhone', 'class' => 'form-control')) }}
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="businessMobile">{{ trans('salon.location_mobile_phone') }}</label>
                                                    {{ Form::text('business_mobile', $salon->mobile_phone, array('id' => 'businessMobile', 'class' => 'form-control')) }}
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="businessType">{{ trans('salon.business_type') }}</label>
                                                    <select name="business_type" class="form-control" id="businessType">
                                                        @foreach($business_type as $key=>$type)
                                                        <option value="{{ $key }}" @if(isset($salon->business_type) && $salon->business_type === $key) selected @endif>{{ $type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="businessAddress">{{ trans('salon.salon_address') }}</label>
                                                    {{ Form::text('business_address', $salon->address, array('id' => 'businessAddress', 'class' => 'form-control')) }}
                                                </div>
                                                <div class="form-group">
                                                    <label for="businessCity">{{ trans('salon.salon_city') }}</label>
                                                    {{ Form::text('business_city', $salon->city, array('id' => 'businessCity', 'class' => 'form-control')) }}
                                                </div>
                                            </div>
                                            <div class="col-lg-6 radio-time">
                                                <div class="form-group">
                                                    <label for="businessCountry">{{ trans('salon.salon_country') }}</label>
                                                    <select name="business_country" class="form-control" id="businessCountry">
                                                        @foreach($countries as $country)
                                                        <option value="{{ $country->country_identifier }}" @if(isset($salon->country) && ($salon->country == $country->country_identifier)) ? selected : null @endif >{{ $country->country_local_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="businessZip">{{ trans('salon.salon_zip') }}</label>
                                                    {{ Form::text('business_zip', $salon->zip_code, array('id' => 'businessZip', 'class' => 'form-control')) }}
                                                </div>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="time-format" for="time_format">{{ trans('salon.time_format') }}<small class="text-muted"> ({{ trans('salon.time_format_desc') }})</small></label>
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="timeFormat1" value="time-24" name="time" @if($salon->time_format === 'time-24') ? checked : null @endif>
                                                        <label for="timeFormat1">0/24</label>
                                                    </div>
                                                    <div class="radio radio-inline">
                                                        <input type="radio" id="timeFormat2" value="time-ampm" name="time" @if($salon->time_format === 'time-ampm') ? checked : null @endif>
                                                        <label for="timeFormat2">AM/PM</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="businessCurrency">{{ trans('salon.business_currency') }}</label>
                                                    <select name="business_currency" id="businessCurrency" class="form-control">
                                                        @foreach($currency_list as $currency)
                                                        <option value="{{ $currency['code'] }}" @if($salon->currency === $currency['code']) ? selected : null @endif>{{ $currency['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="weekStart">{{ trans('salon.week_start') }}</label>
                                                    <select name="week_start" id="weekStart" class="form-control">
                                                        <option value="1" @if($salon->week_starting_on == 1) ? selected @endif>{{ trans('salon.Monday') }}</option>
                                                        <option value="2" @if($salon->week_starting_on == 2) ? selected @endif>{{ trans('salon.Sunday') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="timeZone">{{ trans('salon.salon_time_zone') }}</label>
                                                    <select name="time_zone" id="timeZone" class="form-control">
                                                        <option value="0" default selected>{{ trans('salon.select_time_zone') }}</option>
                                                        @foreach($time_zones as $key=>$zone)
                                                        <option value="{{ $key }}" @if(isset($salon->time_zone) && $salon->time_zone === $key) selected @endif>{{ $key }} - {{ $zone }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="ibox float-e-margins">
                                        <div class="ibox-title">
                                            <h3 class="text-center">Salon logo</h3>
                                            <div class="row">
                                                <img class="salon-logo-img" @if(isset($salon->logo)) ? src="{{ URL::to('/').'/images/salon-logo/'.$salon->logo }}" : src="{{ URL::to('/').'/images/user_placeholder.png' }}" @endif>
                                                <label class="text-center" id="new-image-label" for="image-file">{{ trans('salon.update_salon_logo') }}</label>
                                                <div class="image-container">
                                                    <input class="image-file" id="salonLogo" type="file" name="salon_logo">
                                                    <label tabindex="0" for="salonLogo" class="image-change">{{ trans('salon.select_file') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="text-center">
                                    <button type="button" class="btn btn-success" onclick="submitSalonInfo()">{{ trans('salon.update_salon_info') }}</button>
                                </div>
                            </div>

                            {{ Form::close() }}
                        </div>
                    </div>
                    
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">

                            <div class="ibox-content m-b">
                                <h2>{{ trans('salon.allow_online_payments') }}</h2>
                                <div class="form-group">
                                    <div class="radio radio-info radio-inline">
                                        <input type="radio" id="onlinePaymentsOn" class="online-payments-radio online-radio-on" value="1" name="online_payments" @if($salon->online_payments) checked @endif>
                                        <label for="onlinePaymentsOn">{{ trans('salon.radio_yes') }}</label>
                                    </div>
                                    <div class="radio radio-inline">
                                        <input type="radio" id="onlinePaymentsOff" class="online-payments-radio" value="0" name="online_payments" @if(!$salon->online_payments) checked @endif>
                                        <label for="onlinePaymentsOff">{{ trans('salon.radio_no') }}</label>
                                    </div>
                                </div>
                                <hr>
                                <div class="payment-options-wrap @if($salon->online_payments === 0) hidden @endif">
                                    <small class="text-muted">{{ trans('salon.list_of_payment_options') }}</small>
                                    @if($salon->country != 'hr')
                                    <div id="paypalPayment" class="form-group @if($paypal === null) hidden @endif">
                                        <a href="https://www.paypal.com" class="payment-logo"><img src="{{ URL::to('/').'/images/payment/PayPal.png' }}"></a>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="paypalOn" class="paypal-radio paypal-radio-on" value="1" name="paypalpayment" @if($paypal != null || $paypal->status != 0) checked @endif>
                                            <label for="paypalOn">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="paypalOff" class="paypal-radio" value="0" name="paypalpayment" @if($paypal === null || $paypal->status === 0) checked @endif>
                                            <label for="paypalOff">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                        <div class="payment-keys paypal-key-wrap @if($paypal === null || $paypal->status === 0) hidden @endif">
                                            <div class="form-group">
                                                <label for="PayPalPublic">Client id</label>
                                                <input type="text" id="PayPalPublic" class="form-control" @if($paypal != null) value="{{ $paypal->public_key }}" @endif name="paypal_public_key">
                                            </div>
                                            <div class="form-group">
                                                <label for="PayPalPrivate">Secret key</label>
                                                <input type="text" id="PayPalPrivate" class="form-control" @if($paypal != null) value="{{ $paypal->private_key }}" @endif name="paypal_private_key">
                                            </div>
                                        </div>
                                    </div>
                                    <div id="stripePayment" class="form-group @if($stripe === null) hidden @endif">
                                        <a href="https://stripe.com" class="payment-logo"><img src="{{ URL::to('/').'/images/payment/stripe.png' }}"></a>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="stripeOn" class="stripe-radio stripe-radio-on" value="1" name="stripepayment" @if($stripe != null || $stripe->status != 0) checked @endif>
                                            <label for="stripeOn">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="stripeOff" class="stripe-radio" value="0" name="stripepayment" @if($stripe === null || $stripe->status === 0) checked @endif>
                                            <label for="stripeOff">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                        <div class="payment-keys stripe-key-wrap @if($stripe === null || $stripe->status === 0) hidden @endif">
                                            <div class="form-group">
                                                <label for="StripePublishableKey">Publishable key</label>
                                                <input type="text" id="StripePublishableKey" class="form-control" @if($stripe != null) value="{{ $stripe->public_key }}" @endif name="stripe_publishable_key">
                                            </div>
                                            <div class="form-group">
                                                <label for="StripeSecretKey">Secret key</label>
                                                <input type="text" id="StripeSecretKey" class="form-control" @if($stripe != null) value="{{ $stripe->private_key }}" @endif name="stripe_private_key">
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div id="wspayPayment" class="form-group @if($wspay === null) hidden @endif">
                                        <a href="https://www.wspay.info/" class="payment-logo"><img src="{{ URL::to('/').'/images/payment/wspay.png' }}"></a>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="wspayOn" class="wspay-radio wspay-radio-on" value="1" name="wspayment" @if($wspay != null || $wspay->status != 0) checked @endif>
                                            <label for="wspayOn">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="wspayOff" class="wspay-radio" value="0" name="wspayment" @if($wspay === null || $wspay->status === 0) checked @endif>
                                            <label for="wspayOff">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                        <div class="payment-keys wspay-key-wrap @if($wspay === null || $wspay->status === 0) hidden @endif">
                                            <div class="form-group">
                                                <label for="WsPayShopId">Shop id</label>
                                                <input type="text" id="WsPayShopId" class="form-control" @if($paypal != null) value="{{ $wspay->public_key }}" @endif name="wspay_shop_id">
                                            </div>
                                            <div class="form-group">
                                                <label for="WsPayPrivate">Secret key</label>
                                                <input type="text" id="WsPayPrivate" class="form-control" @if($paypal != null) value="{{ $wspay->private_key }}" @endif name="wspay_private_key">
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <hr>
                                    <button type="button" class="btn btn-success" onclick="submitPaymentSettings()">{{ trans('salon.submit') }}</button>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        var salon_info_route = '{{ route('updateSalon') }}';
        var billing_info_route = '{{ route('postBillingInfo') }}';
    </script>

    @if(session('active_tab'))
    <script>
        $(document).ready(function() {
            var id = '{{ session("active_tab") }}';
            $('#tab-1').removeClass('active');
            $('#tab-2').removeClass('active');
            $('#tab-1-li').removeClass('active');
            $('#tab-2-li').removeClass('active');
            $('#tab-' + id).addClass('active');
            $('#tab-' + id + '-li').addClass('active');
        })
    </script>
    @endif
@endsection