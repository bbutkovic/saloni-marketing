@extends('main')

@section('styles')
    {{ HTML::style('css/plugins/bootstrapselect/bootstrapselect.css') }}
    {{ HTML::style('css/plugins/datepicker/datepicker.css') }}
    {{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
@endsection

@section('scripts')
    {{ HTML::script('js/plugins/bootstrapselect/bootstrapselect.js') }}
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
            <div class="ibox-content">
                <div class="row">
                    <select id="loyaltyTypeSelect" name="loyalty_type" class="form-control m-b-lg">
                        <option value="0" @if(isset($location->loyalty_program) && $location->loyalty_program->loyalty_type === 0) selected @endif>{{ trans('salon.no_loyalty') }}</option>
                        <option value="1" @if(isset($location->loyalty_program) && $location->loyalty_program->loyalty_type === 1) selected @endif>{{ trans('salon.free_booking_opt') }}</option>
                        <option value="2" @if(isset($location->loyalty_program) && $location->loyalty_program->loyalty_type === 2) selected @endif>{{ trans('salon.service_free_opt') }}</option>
                        <option value="3" @if(isset($location->loyalty_program) && $location->loyalty_program->loyalty_type === 3) selected @endif>{{ trans('salon.booking_discount_opt') }}</option>
                    </select>

                    <hr>

                    <div id="noLoyaltyProgram" class="booking-type-wrap m-t">
                        <h4 class="text-muted text-center">{{ trans('salon.no_loyalty_program') }}</h4>
                        <div class="row text-center m-t-lg">
                            <button type="button" class="btn btn-success" onclick="updateLoyaltyProgram(0)">{{ trans('salon.submit') }}</button>
                        </div>
                    </div>

                    <div id="freeBooking" class="booking-type-wrap m-t">
                        <small class="text-muted m-t-lg m-b m-l">{{ trans('salon.free_booking_desc') }}</small>
                        <div class="row m-t m-l">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="arrivalPoints">{{ trans('salon.arrival_points') }}</label>
                                    {{ Form::text('arrival_points', isset($location->loyalty_program->arrival_points) ? $location->loyalty_program->arrival_points : null, array('id' => 'arrivalPoints1', 'class' => 'form-control', 'required')) }}
                                </div>
                                <div class="form-group">
                                    <label for="requiredArrivals">{{ trans('salon.required_arrivals') }}</label>
                                    {{ Form::text('required_arrivals', isset($location->loyalty_program->arrival_points) ? $location->loyalty_program->arrival_points : null, array('id' => 'requiredArrivals1', 'class' => 'form-control', 'required')) }}
                                </div>
                                <div class="form-group">
                                    <label for="maxAmount">{{ trans('salon.free_booking_max_money') }}</label>
                                    <input type="text" id="maxAmount" name="max_amount" @if(isset($location->loyalty_program)) value="{{$location->loyalty_program->max_amount}}" @endif class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="expireDate">{{ trans('salon.points_expire_after') }} <small class="text-muted">{{ trans('salon.points_expire_after_desc') }}</small></label>
                                    {{ Form::text('expire_date', isset($location->loyalty_program->expire_date) ? $location->loyalty_program->expire_date : null, array('id' => 'expireDate1', 'class' => 'form-control', 'required')) }}
                                </div>
                            </div>
                        </div>
                        <div class="row text-center m-t-lg">
                            <button type="button" class="btn btn-success" onclick="updateLoyaltyProgram(1)">{{ trans('salon.submit') }}</button>
                        </div>
                    </div>

                    <div id="serviceFree" class="booking-type-wrap">
                        <small class="text-muted m-t-lg m-b m-l">{{ trans('salon.free_service_desc') }}</small>
                        <div class="row m-t m-l">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="arrivalPoints">{{ trans('salon.arrival_points') }}</label>
                                    {{ Form::text('arrival_points', isset($location->loyalty_program->arrival_points) ? $location->loyalty_program->arrival_points : null, array('id' => 'arrivalPoints2', 'class' => 'form-control', 'required')) }}
                                </div>
                                <div class="form-group">
                                    <label for="requiredArrivals">{{ trans('salon.required_arrivals') }}</label>
                                    {{ Form::text('required_arrivals', isset($location->loyalty_program->arrival_points) ? $location->loyalty_program->arrival_points : null, array('id' => 'requiredArrivals2', 'class' => 'form-control', 'required')) }}
                                </div>
                                <div class="form-group">
                                    <label for="freeServiceSelect">{{ trans('salon.select_group_s') }}</label>
                                    <div class="form-group groups-radio-container">
                                        <div class="radio">
                                            <input type="radio" id="serviceCategory" value="0" name="selected_group" @if(isset($loyalty_settings) && $groups_type == 0) checked @endif>
                                            <label for="serviceCategory">{{ trans('salon.radio_category') }}</label>
                                        </div>
                                        <div class="radio">
                                            <input type="radio" id="serviceGroup" value="1" name="selected_group" @if(isset($loyalty_settings) && $groups_type == 1) checked @endif>
                                            <label for="serviceGroup">{{ trans('salon.radio_group') }}</label>
                                        </div>
                                        <div class="radio">
                                            <input type="radio" id="serviceSubGroup" value="2" name="selected_group" @if(isset($loyalty_settings) && $groups_type == 2) checked @endif>
                                            <label for="serviceSubGroup">{{ trans('salon.radio_subgroup') }}</label>
                                        </div>
                                        <div class="radio">
                                            <input type="radio" id="services" value="3" name="selected_group" @if(isset($loyalty_settings) && $groups_type == 3) checked @endif>
                                            <label for="services">{{ trans('salon.radio_services') }}</label>
                                        </div>
                                        @if(isset($loyalty_settings) && $groups_arr != null)
                                            <select id="serviceGroupSelect" name="group_select[]" class="appended-value form-control selectpicker" multiple data-actions-box="true">
                                                @foreach($group_list as $group_single)
                                                <option value="{{ $groups_type . '-' . $group_single['id'] }}" @if(in_array($group_single['id'], $groups_arr)) selected @endif>{{ $group_single['name'] }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="expireDate">{{ trans('salon.points_expire_after') }} <small class="text-muted">{{ trans('salon.points_expire_after_desc') }}</small></label>
                                    {{ Form::text('expire_date', isset($location->loyalty_program->expire_date) ? $location->loyalty_program->expire_date : null, array('id' => 'expireDate2', 'class' => 'form-control', 'required')) }}
                                </div>
                            </div>
                        </div>
                        <div class="text-center m-t-lg">
                            <button type="button" class="btn btn-success" onclick="updateLoyaltyProgram(2)">{{ trans('salon.submit') }}</button>
                        </div>
                    </div>

                    <div id="bookingDiscount" class="booking-type-wrap">
                        <div class="ibox-content m-b">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row m-b">
                                        <small class="text-muted m-t-lg m-b m-l">{{ trans('salon.booking_discount_desc') }}</small>
                                        <br>
                                        <small class="text-muted m-l">{{ trans('salon.discounts_trans') }}</small>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <label for="socialPoints">{{ trans('salon.social_points') }}</label>
                                            {{ Form::text('social_points', isset($location->loyalty_program->social_points) ? $location->loyalty_program->social_points : null, array('id' => 'socialPoints', 'class' => 'form-control', 'required')) }}
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="referralPoints">{{ trans('salon.referral_points') }}</label>
                                            {{ Form::text('referral_points', isset($location->loyalty_program->referral_points) ? $location->loyalty_program->referral_points : null, array('id' => 'referralPoints', 'class' => 'form-control', 'required')) }}
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="moneySpent">{{ trans('salon.money_spent') }} <small class="text-muted">{{ trans('salon.money_spent_desc') }}</small></label>
                                            {{ Form::text('money_spent', isset($location->loyalty_program->money_spent) ? $location->loyalty_program->money_spent : null, array('id' => 'moneySpent', 'class' => 'form-control', 'required')) }}
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="maxPoints">{{ trans('salon.min_points') }}</label>
                                            {{ Form::text('max_points', isset($location->loyalty_program->max_points) ? $location->loyalty_program->max_points : null, array('id' => 'maxPoints', 'class' => 'form-control', 'required')) }}
                                        </div>
                                        <div class="form-group col-lg-6">
                                            <label for="expireDate">{{ trans('salon.points_expire_after') }} <small class="text-muted">{{ trans('salon.points_expire_after_desc') }}</small></label>
                                            {{ Form::text('expire_date', isset($location->loyalty_program->expire_date) ? $location->loyalty_program->expire_date : null, array('id' => 'expireDate3', 'class' => 'form-control', 'required')) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-xs-12">
                                            <label for="faceShareTitle">{{ trans('salon.share_title') }}</label>
                                            {{ Form::text('share_title', isset($location->loyalty_program->share_title) ? $location->loyalty_program->share_title : null, array('id' => 'faceShareTitle', 'class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group col-xs-12">
                                            <label for="faceShareDesc">{{ trans('salon.share_desc') }}</label>
                                            {{ Form::textarea('share_title', isset($location->loyalty_program->share_desc) ? $location->loyalty_program->share_desc : null, array('id' => 'faceShareDesc', 'class' => 'form-control', 'rows' => 4)) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="services-discounts-container col-md-6">
                                    <button type="button" id="spawnBtn" class="btn btn-success m-b m-l"><i class="fa fa-plus"></i></button>
                                    <div id="fieldsHolder" class="text-center">
                                        <div class="col-lg-12 discount-container">
                                            <div class="form-group points-needed input-group col-lg-6">
                                                <label for="field_discount">{{ trans('salon.discount_percentage') }}</label>
                                                <input id="field_discount0" type="number" class="form-control field-discount" required="" name="field_discount[0]" type="text">
                                                <span class="input-group-btn">
                                                <button type="button" class="btn btn-default percent-button"><i class="fa fa-percent"></i></button>
                                            </span>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="field_points">{{ trans('salon.number_of_pts') }}</label>
                                                <input id="field_points0" class="form-control field-points" required="" name="field_points[0]" type="number" value="">
                                            </div>
                                        </div>
                                    </div>
                                    @if($loyalty_discounts->isNotEmpty())
                                    <div class="row m-t m-b m-l">
                                        <small class="text-muted">{{ trans('salon.existing_discounts') }}</small>
                                    </div>
                                    <div class="existing-discounts-container">
                                        @foreach($loyalty_discounts as $discount)
                                            <div id="discountGroup{{$discount->id}}" data-id="{{ $discount->id }}" class="col-lg-12 m-t discounts-group">
                                                <div class="form-group col-lg-6">
                                                    <label for="field_discount">{{ trans('salon.discount_percentage').' ('.$discount->discount.'%)' }}</label>
                                                    <input id="field_discount{{$discount->id}}" class="form-control discount-exst-val" required="" name="field_discount[{{ $discount->id }}]" type="number" value="{{$discount->discount}}">
                                                </div>
                                                <div class="form-group points-needed input-group col-lg-6">
                                                    <label for="field_points">{{ trans('salon.number_of_pts') }}</label>
                                                    <input id="field_points{{$discount->id}}" class="form-control points-exst-val" required="" name="field_points[{{$discount->id}}]" type="number" value="{{$discount->points}}">
                                                    <span class="input-group-btn">
                                                        <button type="button" class="btn btn-danger delete-block" onclick="deleteDiscount({{ $discount->id }})"><i class="fa fa-trash"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row text-center m-t-lg">
                                <button type="button" class="btn btn-success" onclick="updateLoyaltyProgram(3)">{{ trans('salon.submit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<script>
    var discount_percentage = '{{ trans('salon.discount_percentage') }}';
    var number_of_pts = '{{ trans('salon.number_of_pts') }}';
    var discount_format_error = '{{ trans('salon.discount_format_error') }}';
    var delete_check = '{{ trans('salon.are_you_sure') }}';
    var accept_delete = '{{ trans('salon.accept_delete') }}';
    var cancel = '{{ trans('salon.cancel') }}';

    $('.datepicker').datepicker({
        keyboardNavigation: false,
        forceParse: false,
    });
</script>
@endsection