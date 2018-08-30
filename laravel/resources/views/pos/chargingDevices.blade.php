@extends('main')

@section('styles')
@endsection

@section('scripts')
    {{ HTML::script('js/pos/posSaves.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading pull-left">{{ trans('salon.charging_devices') }}</h2>
            <button class="btn btn-success section-heading pull-right" onclick="addChargingDevice()">Dodaj naplatni uređaj</button>
        </div>
    </div>

    <div id="privacySettings">
        <div class="wrapper wrapper-content">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Oznaka uređaja</th>
                                        <th class="text-center">Oznaka poslovnice</th>
                                        <th class="text-center">Izmijeni</th>
                                        <th class="text-center">Obriši</th>
                                    </tr>
                                </thead>
                                <tbody class="charging-devices-tb">
                                @foreach($charging_devices as $device)
                                    <tr data-id="{{ $device->id }}">
                                        <td class="text-center device-label">{{ $device->device_label }}</td>
                                        <td class="text-center">{{ $device->location_label }}</td>
                                        <td class="text-center">
                                            <a href="#" onclick="editChargingLabel({{ $device->id }})"><i class="fa fa-pencil"></i></a>
                                        </td>
                                        <td class="text-center">
                                            <a href="#" onclick="deleteChargingLabel({{ $device->id }})"><i class="fa fa-trash"></i></a>
                                        </td>
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
    @include('partials.payment.chargingDevice')
    <script>
        var add_device_route = '{{ route('addChargingDevice') }}';
        var delete_device_route = '{{ route('deleteChargingDevice') }}';
    </script>
@endsection