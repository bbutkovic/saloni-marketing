@extends('main')

@section('styles')
{{ HTML::style('css/plugins/dataTables/datatables.min.css') }}
@endsection

@section('content')

<script>
    var change_perm_route = '{{ route("changePermission") }}';
</script>

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12 text-center">
        <h2 class="section-heading">{{ trans('salon.staff_roles') }}</h2>
        <small class="text-center text-muted m-t">{{ trans('salon.staff_roles_desc') }}</small>
    </div>
</div>

<div class="user-settings-wrapper">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">{{ trans('main.permission_type') }}</th>
                                        @foreach($permissions["manage-salon"] as $role=>$val)
                                        @if($role != 1 && $role != 4)
                                        <th class="text-center">{{ trans('main.'.$role) }}</th>
                                        @endif
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="user-table">
                                    @foreach($permissions as $perm => $role)
                                    <tr class="user">
                                        <td class="text-center">{{trans('main.'.$perm)}}</td>
                                        @foreach($role as $rola)
                                        <td class="text-center">
                                            <input @if($rola['selected']) checked @endif class="permission-checkbox checkbox" type="checkbox" disabled>
                                        </td>
                                        @endforeach
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
<script>
    $(document).ready(function () {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });

</script>
@endsection