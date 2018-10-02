@extends('main')

@section('styles')
{{ HTML::style('css/plugins/spectrum/spectrum.css') }}
@endsection

@section('scripts')
{{ HTML::script('js/plugins/dataTables/datatables.min.js') }}
{{ HTML::script('js/plugins/spectrum/spectrum.js') }}
{{ HTML::script('js/booking/bookingSettings.js') }}
{{ HTML::script('js/clients.js') }}
@endsection

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 class="section-heading pull-left">{{ trans('salon.clients') }}</h2>
        <a href="#" class="btn btn-default new-location-btn" onclick="addNewClient()"><i class="fa fa-plus"></i> {{ trans('salon.add_new_client') }}</a>
    </div>
</div>

<div id="clientsOptions" class="user-settings-wrapper">
    <div class="wrapper wrapper-content">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li id="tab-1-li" class="active"><a data-toggle="tab" href="#tab-1">{{ trans('salon.client_list') }}</a></li>
                <li id="tab-2-li" class=""><a data-toggle="tab" href="#tab-2">{{ trans('salon.clients_general') }}</a></li>
                <li id="tab-3-li" class=""><a data-toggle="tab" href="#tab-3">{{ trans('salon.custom_fields') }}</a></li>
                <li id="tab-4-li" class=""><a data-toggle="tab" href="#tab-4">{{ trans('salon.client_labels') }}</a></li>
                <li id="tab-5-li" class=""><a data-toggle="tab" href="#tab-5">{{ trans('salon.clients_refferals') }}</a></li>
            </ul>
            
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover d-table" >
                                <thead>
                                <tr>
                                    <th class="text-center">{{ trans('salon.first_name') }}</th>
                                    <th class="text-center">{{ trans('salon.last_name') }}</th>
                                    <th class="text-center">{{ trans('salon.email') }}</th>
                                    <th class="text-center">{{ trans('salon.phone') }}</th>
                                    <th class="text-center">{{ trans('salon.address') }}</th>
                                    <th class="text-center">{{ trans('salon.gender') }}</th>
                                    <th class="text-center">{{ trans('salon.label') }}</th>
                                    <th class="text-center">{{ trans('salon.referrer') }}</th>
                                    <th class="text-center">{{ trans('salon.loyalty_points') }}</th>
                                    <th class="text-center">{{ trans('salon.options') }}</th>
                                </tr>
                                </thead>
                                <tbody class="clients-table staff-table">
                                    @foreach($clients as $client)
                                    <tr class="staff-info">
                                        <td><a href="{{ route('viewClientProfile', $client->id) }}">{{ $client->first_name }}</a></td>
                                        <td><a href="{{ route('viewClientProfile', $client->id) }}">{{ $client->last_name }}</a></td>
                                        <td>{{ $client->email }}</td>
                                        <td>{{ $client->phone }}</td>
                                        <td>{{ $client->address }}</td>
                                        <td>@if($client->gender == 1) {{ trans('salon.male') }} @elseif($client->gender ==2) {{ trans('salon.female') }} @else {{ trans('salon.undefined') }} @endif</td>
                                        <td>
                                            <select name="client_label" class="client-label-select form-control client-label-{{ $client->id }}" onchange="updateClientLabel({{ $client->id }})">
                                                <option value="0">{{ trans('salon.set_label') }}</option>
                                                @foreach($client_labels as $label)
                                                <option value="{{ $label->id }}" @if($client->label === $label->id) selected @endif>{{ $label->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="client_referral" class="client-referral-select form-control client-referral-{{ $client->id }}" onchange="updateClientReferral({{ $client->id }})">
                                                <option value="0">{{ trans('salon.client_referral') }}</option>
                                                @foreach($client_referrals as $referral)
                                                <option value="{{ $referral->id }}" @if($client->referral === $referral->id) selected @endif>{{ $referral->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>{{ $client->loyalty_points }}</td>
                                        <td class="user-options">
                                            <a href="{{ route('viewClientProfile', $client->id) }}">
                                                <i class="fa fa-pencil table-profile"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="tab-2" class="tab-pane">
                    <div class="panel-body">
                        <div class="ibox-content">
                            {{ Form::open(array('id' => 'updateClientSettings', 'class' => 'm-t m-b')) }}
                            <h4 class="text-center">{{ trans('salon.required_fields_new_client') }}</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center">{{ trans('salon.first_name') }}</th>
                                            <th class="text-center">{{ trans('salon.last_name') }}</th>
                                            <th class="text-center">{{ trans('salon.phone') }}</th>
                                            <th class="text-center">{{ trans('salon.email') }}</th>
                                            <th class="text-center">{{ trans('salon.address') }}</th>
                                            <th class="text-center">{{ trans('salon.gender') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <th class="text-center"><input class="checkbox-notify" type="checkbox" name="first_name" checked disabled></th>
                                            <th class="text-center"><input class="checkbox-notify" type="checkbox" name="last_name" checked disabled></th>
                                            <th class="text-center"><input class="checkbox-notify phone-checkbox" type="checkbox" name="phone" @if(isset($salon->client_fields) && $salon->client_fields->phone === 1) checked @endif></th>
                                            <th class="text-center"><input class="checkbox-notify" type="checkbox" name="email_addr" checked disabled></th>
                                            <th class="text-center"><input class="checkbox-notify address-checkbox" type="checkbox" name="address" @if(isset($salon->client_fields) && $salon->client_fields->address === 1) checked @endif></th>
                                            <th class="text-center"><input class="checkbox-notify gender-checkbox" type="checkbox" name="gender" @if(isset($salon->client_fields) && $salon->client_fields->gender === 1) checked @endif></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row m-t">
                                <h4 class="text-center">{{ trans('salon.notify_via') }}</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">SMS</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Viber</th>
                                                <th class="text-center">Facebook</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-center">
                                                <th class="text-center"><input class="checkbox-notify sms-checkbox" type="checkbox" name="sms" @if($salon->client_settings->sms === 1) checked @endif></th>
                                                <th class="text-center"><input class="checkbox-notify email-checkbox" type="checkbox" name="email" @if($salon->client_settings->email === 1) checked @endif></th>
                                                <th class="text-center"><input class="checkbox-notify viber-checkbox" type="checkbox" name="viber" @if($salon->client_settings->viber === 1) checked @endif></th>
                                                <th class="text-center"><input class="checkbox-notify facebook-checkbox" type="checkbox" name="facebook" @if($salon->client_settings->facebook === 1) checked @endif></th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <h4 class="text-center">{{ trans('salon.clients_name_format') }}</h4>
                                <div class="form-group">
                                    <select id="clientNameFormat" class="form-control" name="name_format">
                                        <option value="1" @if($salon->client_settings->name_format === 'first_last') selected @endif>{{ trans('salon.first_last') }}</option>
                                        <option value="2" @if($salon->client_settings->name_format === 'last_first') selected @endif>{{ trans('salon.last_first') }}</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success m-t text-center">{{ trans('salon.update') }}</button>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
                
                <div id="tab-3" class="tab-pane">
                    <div class="col-lg-12">
                        {{ Form::open(array('id' => 'updateFields')) }}
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
                                                    @foreach($custom_fields as $field)
                                                        <tr id="field-{{ $field->id }}" data-id="{{ $field->id }}">
                                                            <td class="title-field">{{ $field->field_title }}</td>
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
                                @if($custom_fields->isEmpty())
                                <h1 class="text-center">{{ trans('salon.add_custom_fields') }}</h1>
                                @endif
                                
                                <div class="row">
                                    <button type="button" class="btn btn-default m-l" onclick="addNewField('booking')">{{ trans('salon.add_new_field') }}</button>
                                </div>
                                
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                    @include('partials.newField')
                    @include('partials.editCustomField')
                </div>
                
                <div id="tab-4" class="tab-pane client-labels">
                    <div class="panel-body">
                        <div class="ibox-content">
                            <button class="btn btn-default" type="button" onclick="addNewLabel()">{{ trans('salon.add_label') }}</button>
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <h4 class="text-left">{{ trans('salon.client_labels') }}</h4>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('salon.label_name') }}</th>
                                            <th>{{ trans('salon.label_color') }}</th>
                                            <th>{{ trans('salon.options') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($client_labels as $label)
                                        <tr id="label-{{ $label->id }}" data-label="{{ $label->id }}">
                                            <td class="label-name">{{ $label->name }}</td>
                                            <td><span class="color-shape" style="background-color: {{ $label->color }};"></span></td>
                                            <td class="user-options">
                                                @if($label->salon_id != 'all')
                                                <a href="#" class="label-edit" data-id="{{ $label->id }}" data-name="{{ $label->name }}" data-color="{{ $label->color }}" onclick="updateLabel({{ $label->id }})">
                                                    <i class="fa fa-pencil table-profile"></i>
                                                </a>
                                                <a href="#" data-id="{{ $label->id }}" onclick="deleteLabel({{ $label->id }})">
                                                    <i class="fa fa-trash table-delete"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>
                    </div>
                </div>
                
                <div id="tab-5" class="tab-pane client-referral">
                    <div class="panel-body">
                        <div class="ibox-content">
                            <button class="btn btn-default" type="button" onclick="addNewReferral()">{{ trans('salon.add_referral') }}</button>
                            <div class="col-lg-3"></div>
                            <div class="col-lg-6">
                                <h4 class="text-left">{{ trans('salon.client_referrals') }}</h4>
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ trans('salon.referral_name') }}</th>
                                            <th>{{ trans('salon.options') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($salon->client_referrals as $referral)
                                        <tr id="referral-{{ $referral->id }}" data-label="{{ $referral->id }}">
                                            <td class="referral-name">{{ $referral->name }}</td>
                                            <td class="user-options">
                                                @if($referral->salon_id != 'all')
                                                <a href="#" class="referral-edit" data-id="{{ $referral->id }}" data-name="{{ $referral->name }}" onclick="updateReferral({{ $referral->id }})">
                                                    <i class="fa fa-pencil table-profile"></i>
                                                </a>
                                                <a href="#" data-id="{{ $referral->id }}" onclick="deleteReferral({{ $referral->id }})">
                                                    <i class="fa fa-trash table-delete"></i>
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-3"></div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

@include('partials.booking.newClientAlt')
@include('partials.clients.clientLabel')
@include('partials.clients.clientReferral')

<script>
    var update_settings_route = '{{ route('updateClientSettings') }}';
    var update_fields_route = '{{ route('addNewField') }}';
    var edit_field_route = '{{ route('editCustomField') }}';

    var updated = '{{ trans('salon.updated_successfuly') }}';
    var update_failed = '{{ trans('salon.error_updating') }}';
    var select_field = '{{ trans('salon.select_field') }}';
    var change_field_type = '{{ trans('salon.change_field_type') }}';
    var field_text_trans = '{{ trans('salon.field_text') }}';
    var field_multiple_select_trans = '{{ trans('salon.field_multiple_select') }}';
    var swal_alert = '{{ trans('salon.trans_delete_check') }}';
    var field_deleted = '{{ trans('salon.field_deleted') }}';
    var delete_failed = '{{ trans('salon.delete_failed') }}';
    var field_location = 'clients';
    var password_trans = '{{ trans('auth.password') }}';
    var password_confirm_trans = '{{ trans('auth.password_confirmation') }}';
    
    $(document).ready(function() {
        $('.d-table').DataTable({
            pageLength: 7,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'excel', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 7] }, title: 'Clients'},
                {extend: 'pdf', exportOptions: { columns: [0, 1, 2, 3, 4, 5, 7] }, title: 'Clients'},
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
    });
    
    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

    elems.forEach(function(html) {
        var switchery = new Switchery(html);
    });
</script>
    
@endsection