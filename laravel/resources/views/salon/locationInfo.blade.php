@extends('main')

@section('styles')
    {{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
@endsection

@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyColmGdPetW0zIga7qqyByHrll4kMzJVJE"></script>
    {{ HTML::script('js/plugins/jasny/jasny-bootstrap.min.js') }}
    {{ HTML::script('js/salon/salonInfo.js') }}
    {{ HTML::script('js/plugins/dropzone/dropzone.js') }}
@endsection

@section('scripts-footer')
{{ HTML::script('js/googlemaps.js') }}
@endsection

@section('content')
    @if($status === 1)
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <a href="{{ route('newLocation') }}" class="btn btn-default new-location-btn"><i class="fa fa-plus"></i> {{ trans('salon.add_new_location') }}</a>
            <a href="#" class="btn btn-danger new-location-btn" onclick="deleteLocation()"><i class="fa fa-trash"></i> {{ trans('salon.delete_location') }}</a>
        </div>
    </div>
    
    <div id="location-options" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li id="tab-1-li" class="active"><a data-toggle="tab" href="#tab-1">{{ trans('salon.info') }}</a></li>
                    <li id="tab-2-li" class=""><a data-toggle="tab" href="#tab-2">{{ trans('salon.location_hours') }}</a></li>
                    <li id="tab-3-li" class=""><a data-toggle="tab" href="#tab-3">{{ trans('salon.location_gallery') }}</a></li>
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
                                                {{ Form::hidden('location_id', $location->id, array('id' => 'locationId')) }}
                                                <div class="form-group">
                                                    <label for="locationName">{{ trans('salon.location_name') }}*</label>
                                                    {{ Form::text('location_name', $location->location_name, array('id' => 'locationName', 'class' => 'form-control', 'required')) }}
                                                </div>
                                                <div class="form-group">
                                                    <label for="locationPhone">{{ trans('salon.location_phone') }}</label>
                                                    {{ Form::text('location_phone', $location->business_phone, array('id' => 'locationPhone', 'class' => 'form-control')) }}
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="locationMobile">{{ trans('salon.location_mobile_phone') }}</label>
                                                    {{ Form::text('location_mobile_phone', $location->mobile_phone, array('id' => 'locationMobile', 'class' => 'form-control')) }}
                                                </div>
                                                <div class="form-group">
                                                    <label for="locationEmail">{{ trans('salon.business_email') }}</label>
                                                    {{ Form::email('location_email', $location->email_address, array('id' => 'locationEmail', 'class' => 'form-control')) }}
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
                                            <div class="col-lg-8">
                                                <div id="salon-location-map"></div>
                                            </div>
                                            {{ Form::hidden('location_lat', isset($location->lat) ? $location->lat : null, array('id' => 'location_lat')) }}
                                            {{ Form::hidden('location_lng', isset($location->lng) ? $location->lng : null, array('id' => 'location_lng')) }}
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label for="locationAddress">{{ trans('salon.location_address') }}*</label>
                                                    {{ Form::text('location_address', $location->address, array('id' => 'locationAddress', 'class' => 'form-control')) }}
                                                </div>
                                                <div class="form-group">
                                                    <label for="locationCity">{{ trans('salon.salon_city') }}*</label>
                                                    {{ Form::text('location_city', $location->city, array('id' => 'locationCity', 'class' => 'form-control')) }}
                                                </div>
                                                <div class="form-group">
                                                    <label for="locationZip">{{ trans('salon.salon_zip') }}*</label>
                                                    {{ Form::text('location_zip', $location->zip, array('id' => 'locationZip', 'class' => 'form-control')) }}
                                                </div>
                                                <div class="form-group">
                                                    <label for="locationCountry">{{ trans('salon.salon_country') }}*</label>
                                                    <select name="location_country" class="form-control" id="locationCountry">
                                                        @foreach($countries as $country)
                                                        <option value="{{ $country->country_identifier }}" @if(isset($location->country) && ($location->country == $country->country_identifier)) ? selected : null @endif >{{ $country->country_local_name }}</option>
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
                                        <div class="row location-additional-info">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="parking">{{ trans('salon.parking') }}</label>
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="parkingRadio1" value="1" name="parking" @if($location->location_extras->parking) ? checked="" : null @endif>
                                                        <label for="parkingRadio1">{{ trans('salon.radio_yes') }}</label>
                                                    </div>
                                                    <div class="radio radio-inline">
                                                        <input type="radio" id="parkingRadio2" value="0" name="parking" @if(!$location->location_extras->parking) ? checked="" : null @endif>
                                                        <label for="parkingRadio2">{{ trans('salon.radio_no') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="credit_cards">{{ trans('salon.credit_cards') }}</label>
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="creditCards1" value="1" name="credit_cards" @if($location->location_extras->credit_cards) ? checked="" : null @endif>
                                                        <label for="creditCards1">{{ trans('salon.radio_yes') }}</label>
                                                    </div>
                                                    <div class="radio radio-inline">
                                                        <input type="radio" id="creditCards2" value="0" name="credit_cards" @if(!$location->location_extras->credit_cards) ? checked="" : null @endif>
                                                        <label for="creditCards2">{{ trans('salon.radio_no') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="disabled_access">{{ trans('salon.disabled_access') }}</label>
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="disabled1" value="1" name="disabled_access" @if($location->location_extras->accessible_for_disabled) ? checked="" : null @endif>
                                                        <label for="disabled1">{{ trans('salon.radio_yes') }}</label>
                                                    </div>
                                                    <div class="radio radio-inline">
                                                        <input type="radio" id="disabled2" value="0" name="disabled_access" @if(!$location->location_extras->accessible_for_disabled) ? checked="" : null @endif>
                                                        <label for="disabled2">{{ trans('salon.radio_no') }}</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label for="wifi">WiFi</label>
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="wifi1" value="1" name="wifi" @if($location->location_extras->wifi) ? checked="" : null @endif>
                                                        <label for="wifi1">{{ trans('salon.radio_yes') }}</label>
                                                    </div>
                                                    <div class="radio radio-inline">
                                                        <input type="radio" id="wifi2" value="0" name="wifi" @if(!$location->location_extras->wifi) ? checked="" : null @endif>
                                                        <label for="wifi2">{{ trans('salon.radio_no') }}</label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="pets">{{ trans('salon.pets') }}</label>
                                                    <div class="radio radio-info radio-inline">
                                                        <input type="radio" id="pets1" value="1" name="pets" @if($location->location_extras->pets) ? checked="" : null @endif>
                                                        <label for="pets1">{{ trans('salon.radio_yes') }}</label>
                                                    </div>
                                                    <div class="radio radio-inline">
                                                        <input type="radio" id="pets2" value="0" name="pets" @if(!$location->location_extras->pets) ? checked="" : null @endif>
                                                        <label for="pets2">{{ trans('salon.radio_no') }}</label>
                                                    </div>
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
                                            <h3 class="text-center">Logo</h3>
                                            <div class="row">
                                                <img class="salon-logo-img @if($location->location_extras->location_photo == null) hidden @endif" src="{{ URL::to('/').'/images/location-logo/'.$location->location_extras->location_photo }}">
                                                <div class="form-group m-l m-r">
                                                    <label for="location_photo">{{ trans('salon.location_photo') }}</label>
                                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                        <span class="input-group-addon btn btn-default btn-file">
                                                            <span class="fileinput-new">{{ trans('salon.select_location_photo') }}</span>
                                                            <input type="file" name="location_photo" id="locationLogo">
                                                        </span>
                                                        <div class="form-control" data-trigger="fileinput">
                                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                            <span class="fileinput-filename">{{ $location->location_extras->location_photo }}</span>
                                                        </div>
                                                        @if(isset($location->location_extras->location_photo) && $location->location_extras->location_photo != null)
                                                            <div id="deleteLocationImage" class="input-group-addon btn btn-danger">
                                                                <i class="fa fa-trash"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center">
                                <button type="button" class="btn btn-success" onclick="submitLocationInfo()">{{ trans('salon.update_location_info') }}</button>
                            </div>
                            
                        {{ Form::close() }}
                        </div>
                    </div>

                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div class="ibox-content">
                                {{ Form::open(array('id' => 'update-hours', 'class' => 'm-t')) }}
                                {{ Form::hidden('location_id', $location->id, array('id' => 'locationId')) }}
                                {{ Form::hidden('time_format', $location->time_format, array('id' => 'time-format')) }}

                                <table class="table table-working-hours">
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ trans('salon.working_hours_day') }}</th>
                                            <th class="text-center">{{ trans('salon.working_hours_status') }}</th>
                                            <th class="text-center">{{ trans('salon.working_hours_start') }}</th>
                                            <th class="text-center">{{ trans('salon.working_hours_end') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($salon->week_starting_on == 2)
                                        <tr>
                                            <td class="text-center">{{ trans('salon.Sunday') }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" id="openSun" class="js-switch" name="open_sun" @if(isset($location_hours[6]->status) && $location_hours[6]->status != 'off') ? checked : null @endif>
                                            </td>
                                            <td>
                                                <select name="time_start_sun" id="time_start_sun" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[6]->start_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="time_end_sun" id="time_end_sun" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[6]->closing_time === $key)) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">{{ trans('salon.Monday') }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" id="open_m" class="js-switch" name="open_m" @if(isset($location_hours[0]->status) && $location_hours[0]->status != 'off') ? checked : null @endif>
                                            </td>
                                            <td>
                                                <select name="time_start_m" id="time_start_m" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[0]->start_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="time_end_m" id="time_end_m" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[0]->closing_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td class="text-center">{{ trans('salon.Monday') }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" id="open_m" class="js-switch" name="open_m" @if(isset($location_hours[0]->status) && $location_hours[0]->status != 'off') ? checked : null @endif>
                                            </td>
                                            <td>
                                                <select name="time_start_m" id="time_start_m" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[0]->start_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="time_end_m" id="time_end_m" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[0]->closing_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="text-center">{{ trans('salon.Tuesday') }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" id="open_t" class="js-switch" name="open_t" @if(isset($location_hours[1]->status) && $location_hours[1]->status != 'off') ? checked : null @endif>
                                            </td>
                                            <td>
                                                <select name="time_start_t" id="time_start_t" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[1]->start_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="time_end_t" id="time_end_t" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[1]->closing_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">{{ trans('salon.Wednesday') }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" id="open_w" class="js-switch" name="open_w" @if(isset($location_hours[2]->status) && $location_hours[2]->status != 'off') ? checked : null @endif>
                                            </td>
                                            <td>
                                                <select name="time_start_w" id="time_start_w" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[2]->start_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="time_end_w" id="time_end_w" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[2]->closing_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">{{ trans('salon.Thursday') }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" id="open_th" class="js-switch" name="open_th" @if(isset($location_hours[3]->status) && $location_hours[3]->status != 'off') ? checked : null @endif>
                                            </td>
                                            <td>
                                                <select name="time_start_th" id="time_start_th" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[3]->start_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="time_end_th" id="time_end_th" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[3]->closing_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">{{ trans('salon.Friday') }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" id="open_f" class="js-switch" name="open_f" @if(isset($location_hours[4]->status) && $location_hours[4]->status != 'off') ? checked : null @endif>
                                            </td>
                                            <td>
                                                <select name="time_start_f" id="time_start_f" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[4]->start_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="time_end_f" id="time_end_f" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[4]->closing_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">{{ trans('salon.Saturday') }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" id="open_sat" class="js-switch" name="open_sat" @if(isset($location_hours[5]->status) && $location_hours[5]->status != 'off') ? checked : null @endif>
                                            </td>
                                            <td>
                                                <select name="time_start_sat" id="time_start_sat" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[5]->start_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="time_end_sat" id="time_end_sat" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[5]->closing_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        @if($salon->week_starting_on == 1)
                                        <tr>
                                            <td class="text-center">{{ trans('salon.Sunday') }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" class="js-switch" name="open_sun" @if(isset($location_hours[6]->status) && $location_hours[6]->status != 'off') ? checked : null @endif>
                                            </td>
                                            <td>
                                                <select name="time_start_sun" id="time_start_sun" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[6]->start_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <select name="time_end_sun" id="time_end_sun" class="form-control">
                                                    @foreach($time_list as $key=>$time)
                                                    <option value="{{$key}}" @if($location_hours[6]->closing_time === $key) ? selected : null @endif>{{ $time }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                
                                <div class="update-timetable text-center">
                                    <button type="button" class="btn btn-success text-center" onclick="submitOpenHours()">{{ trans('salon.save_salon') }}</button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    
                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body">
                            <div class="ibox-content">
                                <small class="text-muted">{{ trans('salon.location_gallery_limit') }}</small>
                                <div class="uploaded-images">
                                    @foreach($location->photos as $photo)
                                    <div class="location-photo" id="photo{{$photo->id}}">
                                        <div class="photo-container" style="background-image: url({{ URL::to('/').'/images/salon-websites/gallery/'.$photo->name }})"></div>
                                        <a href="#" class="delete-photo" onclick="deletePhoto({{ $photo->id }})"><i class="fa fa-trash"></i></a>
                                    </div>
                                    @endforeach
                                </div>
                                <?php $photos_count = count($location->photos); ?>
                                @if($photos_count < 12)
                                <div class="upload-images">
                                    <form action="{{ route('uploadLocationImages') }}" class="dropzone" enctype="multipart/form-data" id="imagesDropzone">
                                        {{ csrf_field() }}
                                        <div class="fallback">
                                            <input type="file" name="file" multiple>
                                        </div>
                                    </form>
                                </div>
                                    <button type="button" class="btn btn-success m-t" id="dropzoneSubmit">{{ trans('salon.submit') }}</button>
                                @else
                                    <small class="text-muted">{{ trans('salon.max_photos_uploaded') }}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        var location_info_route = '{{ route('updateLocation') }}';
        var open_hours_route = '{{ route('updateWorkingHours') }}';
        var billing_info_route = '{{ route('postBillingInfo') }}';
    </script>
    @else
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-6">
            <h2 class="section-heading pull-left">{{ trans('salon.no_locations_added') }}</h2>
        </div>
        <div class="col-lg-6">
            <button type="button" class="btn btn-default copy-salon pull-right m-r" onclick="copyDataFromSalon({{ Auth::user()->salon_id }})">{{ trans('salon.copy_info_from_salon') }}</button>
        </div>
    </div>

    <div class="new-location-wrapper">
        <div class="wrapper wrapper-content">
            {{ Form::open(array('route' => 'createLocation', 'method' => 'post', 'id' => 'create-location', 'class' => 'm-t', 'files' => 'true')) }}
            <div class="row wrapper">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="location_name">{{ trans('salon.location_name') }}*</label>
                                        {{ Form::text('location_name', null, array('id' => 'location_name', 'class' => 'form-control', 'required')) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="location_phone">{{ trans('salon.location_phone') }}</label>
                                        {{ Form::text('location_phone', null, array('id' => 'location_phone', 'class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="location_mobile_phone">{{ trans('salon.location_mobile_phone') }}</label>
                                        {{ Form::text('location_mobile_phone', null, array('id' => 'location_mobile_phone', 'class' => 'form-control')) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="location_email">{{ trans('salon.business_email') }}</label>
                                        {{ Form::email('location_email', null, array('id' => 'location_email', 'class' => 'form-control')) }}
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
                                        <label for="location_address">{{ trans('salon.salon_address') }}*</label>
                                        {{ Form::text('location_address', null, array('id' => 'location_address', 'class' => 'form-control', 'required')) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="location_city">{{ trans('salon.salon_city') }}*</label>
                                        {{ Form::text('location_city', null, array('id' => 'location_city', 'class' => 'form-control', 'required')) }}
                                    </div>
                                </div>
                                <div class="col-lg-6 radio-time">
                                    <div class="form-group">
                                        <label for="location_zip">{{ trans('salon.salon_zip') }}*</label>
                                        {{ Form::text('location_zip', null, array('id' => 'location_zip', 'class' => 'form-control', 'required')) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="location_country">{{ trans('salon.salon_country') }}*</label>
                                        <select name="location_country" class="form-control">
                                            @foreach($countries as $country)
                                            <option value="{{ $country->country_identifier }}">{{ $country->country_local_name }}</option>
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
                                        <label for="billing-oib">{{ trans('salon.billing_oib') }}</label>
                                        {{ Form::text('billing_oib', null, array('id' => 'billing_oib', 'class' => 'form-control')) }}
                                    </div>
                                    <div class="form-group">
                                        <label for="billing-iban">IBAN</label>
                                        {{ Form::text('billing_iban', null, array('id' => 'billing_iban', 'class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="billing_swift">SWIFT</label>
                                        {{ Form::text('billing_swift', null, array('id' => 'billing_swift', 'class' => 'form-control')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <div class="row location-additional-info">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="parking">{{ trans('salon.parking') }}</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="parking-radio1" value="1" name="parking" checked="">
                                            <label for="parking-radio1">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="parking-radio2" value="0" name="parking">
                                            <label for="parking-radio2">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="credit_cards">{{ trans('salon.credit_cards') }}</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="credit-cards-1" value="1" name="credit_cards" checked="">
                                            <label for="credit-cards-1">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="credit-cards-2" value="0" name="credit_cards">
                                            <label for="credit-cards-2">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="disabled_access">{{ trans('salon.disabled_access') }}</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="disabled-1" value="1" name="disabled_access" checked="">
                                            <label for="disabled-1">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="disabled-2" value="0" name="disabled_access">
                                            <label for="disabled-2">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="wifi">WiFi</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="wifi-1" value="1" name="wifi" checked="">
                                            <label for="wifi-1">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="wifi-2" value="0" name="wifi">
                                            <label for="wifi-2">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="pets">{{ trans('salon.pets') }}</label>
                                        <div class="radio radio-info radio-inline">
                                            <input type="radio" id="pets-1" value="1" name="pets" checked="">
                                            <label for="pets-1">{{ trans('salon.radio_yes') }}</label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <input type="radio" id="pets-2" value="0" name="pets">
                                            <label for="pets-2">{{ trans('salon.radio_no') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-success">{{ trans('salon.save_salon') }}</button>
                </div>
            </div>
            {{ Form::close() }}

        </div>
    </div>
    @endif
    <script>
        var swal_confirm = '{{ trans("salon.confirm_booking") }}';
        var swal_cancel = '{{ trans("salon.cancel") }}';
        var prompt = '{{ trans('salon.are_you_sure') }}';
        var delete_desc = '{{ trans('salon.delete_desc') }}';
        var delete_location = '{{ trans('salon.delete_location') }}';
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });

        Dropzone.options.imagesDropzone = {

            autoProcessQueue: false,

            init: function() {
                var submitButton = document.querySelector("#dropzoneSubmit")
                    imagesDropzone = this; // closure

                submitButton.addEventListener("click", function() {
                    imagesDropzone.processQueue();
                });

                this.on("success", function() {
                   imagesDropzone.options.autoProcessQueue = true;
                });

                this.on('error', function(file, message, xhr) {
                    var header = xhr.responseText;
                    toastr.error(header);
                    this.removeFile(file);
                });

                this.on('complete', function (message) {
                    if(this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        toastr.success('{{ trans('salon.updated_successfuly') }}');
                    }
                });

            }
        };

    </script>
@endsection

