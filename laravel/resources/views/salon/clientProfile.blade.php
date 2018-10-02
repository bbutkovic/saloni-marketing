@extends('main')

@section('styles')
    {{ HTML::style('css/plugins/datepicker/datepicker.css') }}
@endsection

@section('scripts')
    {{ HTML::script('js/clients/clientProfile.js') }}
    {{ HTML::script('js/plugins/dataTables/datatables.min.js') }}
    {{ HTML::script('js/plugins/datepicker/datepicker.js') }}
@endsection

@section('content')

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading">{{ $client->first_name }} {{ $client->last_name }} @if($client->note != null) - {{ $client->note }} @endif</h2>
        </div>
    </div>
    
    <div id="location-options" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li id="tab-1-li" class="active"><a data-toggle="tab" href="#tab-1">{{ trans('salon.client_info') }}</a></li>
                    <li id="tab-2-li" class=""><a data-toggle="tab" href="#tab-2">{{ trans('salon.client_booking') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <div class="ibox-content">
                                <h4 class="text-muted text-center m-t m-b">{{ trans('salon.base_info') }}</h4>

                                {{ Form::open(array('id' => 'clientProfileForm', 'class' => 'm-t')) }}
                                    {{ Form::hidden('client_id', $client->id, array('id' => 'clientId')) }}
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="firstName">{{ trans('salon.first_name') }}</label>
                                            {{ Form::text('first_name', $client->first_name, array('id' => 'firstName', 'class' => 'form-control', 'required')) }}
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="lastName">{{ trans('salon.last_name') }}</label>
                                            {{ Form::text('last_name', $client->last_name, array('id' => 'lastName', 'class' => 'form-control', 'required')) }}
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">{{ trans('salon.email') }}</label>
                                            {{ Form::text('email', $client->email, array('id' => 'email', 'class' => 'form-control', 'required')) }}
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="phone">{{ trans('salon.phone') }}</label>
                                            {{ Form::text('phone', $client->phone, array('id' => 'phone', 'class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="address">{{ trans('salon.address') }}</label>
                                            {{ Form::text('address', $client->address, array('id' => 'address', 'class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="city">{{ trans('salon.city') }}</label>
                                            {{ Form::text('city', $client->city, array('id' => 'city', 'class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="zip">{{ trans('salon.salon_zip') }}</label>
                                            {{ Form::text('zip', $client->zip, array('id' => 'zip', 'class' => 'form-control')) }}
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label for="gender">{{ trans('salon.gender') }}</label>
                                            <select name="gender" id="gender" class="form-control" required>
                                                @if($client->gender != 1 && $client->gender != 2)
                                                <option value="" selected>{{ trans('salon.select_gender') }}</option>
                                                @endif
                                                <option value="1" @if($client->gender === 1) selected @endif>{{ trans('salon.male') }}</option>
                                                <option value="2" @if($client->gender === 2) selected @endif>{{ trans('salon.female') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="birthday">{{ trans('salon.birthday') }}</label>
                                            {{ Form::text('birthday', $client->birthday, array('id' => 'birthday', 'class' => 'form-control client-birthday')) }}
                                        </div>
                                        
                                        @if($client->account === null)
                                        <div class="col-lg-12">
                                            <div class="form-group col-lg-6">
                                                <label class="services-label" for="allow_sms_reminders">{{ trans('salon.allow_sms_reminders') }}</label>
                                                <div class="radio radio-info radio-inline">
                                                    <input type="radio" id="allowSmsReminders" name="allow_sms_reminders" @if($client->sms_reminders === 1) checked @endif>
                                                    <label for="allowSmsReminders">{{ trans('salon.radio_yes') }}</label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <input type="radio" name="allow_sms_reminders" @if($client->sms_reminders != 1) checked @endif>
                                                    <label for="allow_sms_reminders">{{ trans('salon.radio_no') }}</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label class="services-label" for="allow_sms_marketing">{{ trans('salon.allow_sms_marketing') }}</label>
                                                <div class="radio radio-info radio-inline">
                                                    <input type="radio" id="allowSmsMarketing" name="allow_sms_marketing" @if($client->sms_marketing === 1) checked @endif>
                                                    <label for="allowSmsMarketing">{{ trans('salon.radio_yes') }}</label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <input type="radio" id="allowSmsMarketing1" name="allow_sms_marketing" @if($client->sms_marketing != 1) checked @endif>
                                                    <label for="allowSmsMarketing1">{{ trans('salon.radio_no') }}</label>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-lg-6">
                                                <label class="services-label" for="allow_email_reminders">{{ trans('salon.allow_email_reminders') }}</label>
                                                <div class="radio radio-info radio-inline">
                                                    <input type="radio" id="allowEmailReminders" name="allow_email_reminders" @if($client->email_reminders === 1) checked @endif>
                                                    <label for="allowEmailReminders">{{ trans('salon.radio_yes') }}</label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <input type="radio" id="allowEmailReminders1" name="allow_email_reminders" @if($client->email_reminders != 1) checked @endif>
                                                    <label for="allowEmailReminders1">{{ trans('salon.radio_no') }}</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label class="services-label" for="allow_email_marketing">{{ trans('salon.allow_email_marketing') }}</label>
                                                <div class="radio radio-info radio-inline">
                                                    <input type="radio" id="allowEmailMarketing" name="allow_email_marketing" @if($client->email_marketing === 1) checked @endif>
                                                    <label for="allowEmailMarketing">{{ trans('salon.radio_yes') }}</label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <input type="radio" id="allowEmailMarketing1" name="allow_email_marketing" @if($client->email_marketing != 1) checked @endif>
                                                    <label for="allowEmailMarketing1">{{ trans('salon.radio_no') }}</label>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-lg-6">
                                                <label class="services-label" for="allow_viber_reminders">{{ trans('salon.allow_viber_reminders') }}</label>
                                                <div class="radio radio-info radio-inline">
                                                    <input type="radio" id="allowViberReminders" name="allow_viber_reminders" @if($client->viber_reminders === 1) checked @endif>
                                                    <label for="allowViberReminders">{{ trans('salon.radio_yes') }}</label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <input type="radio" id="allowViberReminders1" name="allow_viber_reminders" @if($client->viber_reminders != 1) checked @endif>
                                                    <label for="allowViberReminders1">{{ trans('salon.radio_no') }}</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label class="services-label" for="allow_sms_marketing">{{ trans('salon.allow_viber_marketing') }}</label>
                                                <div class="radio radio-info radio-inline">
                                                    <input type="radio" id="allowViberMarketing" name="allow_viber_marketing" @if($client->viber_marketing === 1) checked @endif>
                                                    <label for="allowViberMarketing">{{ trans('salon.radio_yes') }}</label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <input type="radio" id="allowViberMarketing1" name="allow_viber_marketing" @if($client->viber_marketing != 1) checked @endif>
                                                    <label for="allowViberMarketing1">{{ trans('salon.radio_no') }}</label>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-lg-6">
                                                <label class="services-label" for="allow_email_reminders">{{ trans('salon.allow_facebook_reminders') }}</label>
                                                <div class="radio radio-info radio-inline">
                                                    <input type="radio" id="allowFacebookReminders" name="allow_facebook_reminders" @if($client->facebook_reminders === 1) checked @endif>
                                                    <label for="allowFacebookReminders">{{ trans('salon.radio_yes') }}</label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <input type="radio" id="allowFacebookReminders1" name="allow_facebook_reminders" @if($client->facebook_reminders != 1) checked @endif>
                                                    <label for="allowFacebookReminders1">{{ trans('salon.radio_no') }}</label>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label class="services-label" for="allow_facebook_marketing">{{ trans('salon.allow_facebook_marketing') }}</label>
                                                <div class="radio radio-info radio-inline">
                                                    <input type="radio" id="allowFacebookMarketing" name="allow_facebook_marketing" @if($client->facebook_marketing === 1) checked @endif>
                                                    <label for="allowFacebookMarketing">{{ trans('salon.radio_yes') }}</label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <input type="radio" id="allowFacebookMarketing1" name="allow_facebook_marketing" @if($client->facebook_marketing != 1) checked @endif>
                                                    <label for="allowFacebookMarketing1">{{ trans('salon.radio_no') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    <hr>
                                    <h4 class="text-muted text-center m-t m-b">{{ trans('salon.custom_fields') }}</h4>
                                    <div class="row">
                                        @foreach($booking_fields as $field)
                                        <div class="form-group col-md-6">
                                            <label for="{{ $field->field_name }}">{{ $field->field_title }}</label>
                                            @if($field->field_type === '1')
                                            <input type="text" name="{{ $field->field_name }}" value="{{ $client[$field->field_name] }}" class="form-control custom-field-val">
                                            @else
                                            <select name="{{ $field->field_name }}" class="form-control custom-field-val">
                                                @foreach($field->select_options as $option)
                                                <option value="{{$option->option_value}}" @if($client[$field->field_name] === $option->option_value) selected @endif>{{ $option->option_name }}</option>
                                                @endforeach
                                            </select>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                <div class="row text-center">
                                    <button type="submit" class="btn btn-success m-t-lg m-b-lg">{{ trans('salon.update') }}</button>
                                </div>
                                
                                {{ Form::close() }}
                                
                            </div>
                        </div>
                    </div>
                    
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div class="ibox-content">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover d-table clients-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">{{ trans('salon.service_name_tbl') }}</th>
                                                <th class="text-center">{{ trans('salon.staff_tbl') }}</th>
                                                <th class="text-center">{{ trans('salon.date') }}</th>
                                                <th class="text-center">{{ trans('salon.booking_time') }}</th>
                                                <th class="text-center">{{ trans('salon.price') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="staff-table">
                                            @foreach($client_bookings as $booking)
                                            <tr>
                                                <td>{{ $booking->service->service_details->name }}</td>
                                                <td>{{ $booking->staff->user_extras->first_name }}</td>
                                                <td>{{ $booking->booking_date }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->booking_end)->format('H:i') }}</td>
                                                <td>{{ $booking->service->service_details->base_price }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
<script>

    var update_profile_route = '{{ route('updateClientInfo') }}';

    $('.client-birthday').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        startView: 'decades',
    });
    
    $('.d-table').DataTable({
        pageLength: 20,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'excel', exportOptions: { columns: [1, 2, 3] }, title: 'Staff'},
            {extend: 'pdf', exportOptions: { columns: [1, 2, 3] }, title: 'Staff'},
            {extend: 'print',
            customize: function (win){
                $(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '10px');
                $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ]
    });
</script>
    
@endsection

 