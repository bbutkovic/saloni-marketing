@extends('main')

@section('styles')
@endsection

@section('scripts')
    {{ HTML::script('js/plugins/dataTables/datatables.min.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading pull-left">{{ trans('salon.salons_management') }}</h2>
        </div>
    </div>

    <div id="privacySettings">
        <div class="wrapper wrapper-content">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover d-table" >
                            <thead>
                                <tr>
                                    <th class="text-center">#ID</th>
                                    <th class="text-center">{{ trans('salon.name') }}</th>
                                    <th class="text-center">{{ trans('salon.contact_name') }}</th>
                                    <th class="text-center">{{ trans('salon.email') }}</th>
                                    <th class="text-center">{{ trans('salon.location_number') }}</th>
                                    <th class="text-center">{{ trans('salon.staff_number') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="superadmin-salons-table">
                            @foreach($salons as $salon)
                                <tr data-id="{{ $salon->id }}">
                                    <td class="text-center">{{ $salon->id }}</td>
                                    <td class="text-center">{{ $salon->business_name }}</td>
                                    <td class="text-center">{{ $salon->contact_name }}</td>
                                    <td class="text-center">{{ $salon->email_address }}<br>{{ $salon->business_phone }}</td>
                                    <td class="text-center">{{ count($salon->locations) }}</td>
                                    <td class="text-center">{{ count($salon->salon_staff) }}</td>
                                    <td class="user-options">
                                        <a href="#" onclick="deleteSalon({{ $salon->id }})">
                                            <i class="fa fa-trash table-delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.delete_salon') }}"></i>
                                        </a>
                                        <a href="{{ route('signInAsAdmin', $salon->id) }}">
                                            <i class="fa fa-sign-in table-signin" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.sign_as_salon_admin') }}"></i>
                                        </a>
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
    <script>
        var prompt = '{{ trans('salon.delete_salon_check') }}';
        var delete_salon_route = '{{ route('deleteSalon') }}';
        $('.d-table').DataTable({
            pageLength: 20,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
            ]
        });
    </script>
@endsection