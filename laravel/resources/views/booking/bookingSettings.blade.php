@extends('main')

@section('styles')
{{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
{{ HTML::style('css/plugins/spectrum/spectrum.css') }}
@endsection

@section('scripts')
{{ HTML::script('js/plugins/jasny/jasny-bootstrap.min.js') }}
{{ HTML::script('js/booking/bookingSettings.js') }}
{{ HTML::script('js/booking/booking.js') }}
{{ HTML::script('js/plugins/spectrum/spectrum.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading">{{ trans('salon.online_booking') }}</h2>
            <small class="text-muted">{{ trans('salon.salon_wide') }}</small>
        </div>
    </div>
    
    <div id="location-options" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li id="tab-1-li" class="active"><a data-toggle="tab" href="#tab-1">{{ trans('salon.booking_policies') }}</a></li>
                    <li id="tab-2-li" class=""><a data-toggle="tab" href="#tab-2">{{ trans('salon.booking_display_fields') }}</a></li>
                </ul>
                <div class="tab-content">
                    
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    {{ Form::open(array('id' => 'updatePolicies')) }}
                                    <div class="ibox-title policies-settings">
                                        <div class="col-md-3 col-sm-6 text-center">
                                            <div class="form-group">
                                                <label for="available">{{ trans('salon.staff_selection') }}: </label>
                                                <div class="radio radio-info radio">
                                                    <input type="radio" id="staffSelection1" name="staff_selection" @if(isset(Auth::user()->salon->booking_policy) && Auth::user()->salon->booking_policy->staff_selection === 1) ? checked : null @endif>
                                                    <label for="staffSelection1">{{ trans('salon.radio_yes') }}</label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <input type="radio" id="staffSelection2" name="staff_selection" @if(!isset(Auth::user()->salon->booking_policy) || (isset(Auth::user()->salon->booking_policy) && Auth::user()->salon->booking_policy->staff_selection === 0)) ? checked : null @endif>
                                                    <label for="staffSelection2">{{ trans('salon.radio_no') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 text-center">
                                            <div class="form-group">
                                                <label for="available">{{ trans('salon.show_prices') }}: </label>
                                                <div class="radio radio-info radio">
                                                    <input type="radio" id="showPrices1" name="show_prices" @if(isset(Auth::user()->salon->booking_policy) && Auth::user()->salon->booking_policy->show_prices === 1) ? checked : null @endif>
                                                    <label for="showPrices1">{{ trans('salon.radio_yes') }}</label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <input type="radio" id="showPrices2" name="show_prices" @if(!isset(Auth::user()->salon->booking_policy) || (isset(Auth::user()->salon->booking_policy) && Auth::user()->salon->booking_policy->show_prices === 0)) ? checked : null @endif>
                                                    <label for="showPrices2">{{ trans('salon.radio_no') }}</label>
                                                </div>
                                            </div>
                                        </div>    
                                        <div class="col-md-3 col-sm-6 text-center">
                                            <div class="form-group">
                                                <label for="first_name_only">{{ trans('salon.staff_first_name_only') }}: </label>
                                                <div class="radio radio-info radio">
                                                    <input type="radio" id="firstNameOnly1" name="first_name_only" @if(isset(Auth::user()->salon->booking_policy) && Auth::user()->salon->booking_policy->first_name_only === 1) ? checked : null @endif>
                                                    <label for="firstNameOnly1">{{ trans('salon.radio_yes') }}</label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <input type="radio" id="firstNameOnly2" name="first_name_only" @if(!isset(Auth::user()->salon->booking_policy) || (isset(Auth::user()->salon->booking_policy) && Auth::user()->salon->booking_policy->first_name_only === 0)) ? checked : null @endif>
                                                    <label for="firstNameOnly2">{{ trans('salon.radio_no') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6 text-center">
                                            <div class="form-group">
                                                <label for="multiple_staff">{{ trans('salon.allow_multiple_staff') }}: </label>
                                                <div class="radio radio-info radio">
                                                    <input type="radio" id="multipleStaff1" name="multiple_staff" @if(isset(Auth::user()->salon->booking_policy) && Auth::user()->salon->booking_policy->multiple_staff === 1) ? checked : null @endif>
                                                    <label for="multipleStaff1">{{ trans('salon.radio_yes') }}</label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <input type="radio" id="multipleStaff2" name="multiple_staff" @if(!isset(Auth::user()->salon->booking_policy) || (isset(Auth::user()->salon->booking_policy) && Auth::user()->salon->booking_policy->multiple_staff === 0)) ? checked : null @endif>
                                                    <label for="multipleStaff2">{{ trans('salon.radio_no') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ibox-title time-limits">
                                        <div class="col-lg-6">
                                            <div class="form-group text-center">
                                                <label for="cancelLimit">{{ trans('salon.cancel_limit_desc') }}</label>
                                                <select name="cancel_limit" id="cancelLimit" class="form-control limits-select">
                                                    @foreach($cancel_time as $key=>$cancel)
                                                    <option value="{{ $key }}" @if(isset(Auth::user()->salon->booking_policy) && (Auth::user()->salon->booking_policy->cancel_reschedule_time == $key)) ? selected : null @endif>{{ $cancel }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group text-center">
                                                <label for="bookingSlot">{{ trans('salon.booking_slot') }} </label>
                                                <select name="booking_slot" id="bookingSlot" class="form-control limits-select">
                                                    @foreach($slots as $key=>$slot)
                                                    <option value="{{$key}}" @if(isset(Auth::user()->salon->booking_policy) && (Auth::user()->salon->booking_policy->booking_slot == $key)) ? selected : null @endif>{{ $slot }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center m-t">
                                        <button class="btn btn-success" type="submit">{{ trans('salon.update') }}</button>
                                    </div>
                                    
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                {{ Form::open(array('route' => 'updateDisplayFields', 'id' => 'updateFields')) }}
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <div class="preselected-services">
                                            <div class="service-select">
                                                <div class="table-responsive">
                                                    <table class="table table-striped table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">{{ trans('salon.field_name') }}</th>
                                                                <th class="text-center">{{ trans('salon.field_status') }}</th>
                                                                <th class="text-center">{{ trans('salon.options') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="staff-table">
                                                            @foreach($display_fields as $field)
                                                                <tr id="field-{{ $field->id }}" data-id="{{ $field->id }}">
                                                                    <td class="field-title">{{ $field->field_title }}</td>
                                                                    <td><input type="checkbox" class="js-switch" name="input_status[{{$field->id}}]" @if($field->field_status === '1') ? checked : null @endif></td>
                                                                    <td>
                                                                        <a href="#" data-id="{{ $field->id }}" data-name="{{ $field->field_title }}" class="open-edit-modal">
                                                                            <i class="fa fa-pencil table-profile"></i>
                                                                        </a>
                                                                        <a href="#" data-id="{{ $field->id }}" data-name="{{ $field->field_title }}" onclick="deleteField({{ $field->id }})">
                                                                            <i class="fa fa-trash table-delete"></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div> 
                                        </div>
                                        @if($display_fields->isEmpty())
                                        <h1 class="text-center">{{ trans('salon.add_custom_fields') }}</h1>
                                        @endif
                                        
                                        <div class="row">
                                            @if(count($display_fields) < 3)
                                            <button type="button" class="btn btn-default m-l" onclick="addNewField('booking')">{{ trans('salon.add_new_field') }}</button>
                                            @endif
                                        </div>
                                        
                                    </div>
                                </div>
                                {{ Form::close() }}
                            </div>
                            @include('partials.newField')
                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
    @include('partials.editCustomField')
    
    <script>
        var update_policies_route = '{{ route('updateBookingPolicies') }}';
        var new_field_route = '{{ route('addNewField') }}';
        var edit_field_route = '{{ route('editCustomField') }}';

        var select_field = '{{ trans('salon.select_field') }}';
        var change_field_type = '{{ trans('salon.change_field_type') }}';
        var field_text_trans = '{{ trans('salon.field_text') }}';
        var field_multiple_select_trans = '{{ trans('salon.field_multiple_select') }}';
        var swal_alert = '{{ trans('salon.trans_delete_check') }}';
        var field_deleted = '{{ trans('salon.field_deleted') }}';
        var delete_failed = '{{ trans('salon.delete_failed') }}';
        var field_location = 'booking';
    
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        var custom_field = '{{ trans('salon.custom_field') }}';
        
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });

    </script>
@endsection