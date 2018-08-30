@extends('main')

@section('styles')
@endsection

@section('scripts')
    {{ HTML::script('js/clients/clients.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading pull-left">{{ trans('salon.loyalty_status') }}</h2>
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
                                        <th class="text-center">{{ trans('salon.business') }}</th>
                                        <th class="text-center">{{ trans('salon.location') }}</th>
                                        <th class="text-center">{{ trans('salon.loyalty_points') }}</th>
                                        <th class="text-center">{{ trans('salon.arrival_points') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($loyalty as $loyalty_status)
                                    <tr>
                                        <td class="text-center">{{ $loyalty_status['salon']['business_name'] }}</td>
                                        <td class="text-center">{{ $loyalty_status['location']['location_name'] }}</td>
                                        <td class="text-center">{{ $loyalty_status['loyalty_points'] }}</td>
                                        <td class="text-center">{{ $loyalty_status['arrival_points'] }}</td>
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
@endsection