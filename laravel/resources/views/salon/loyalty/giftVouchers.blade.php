@extends('main')

@section('styles')
    {{ HTML::style('css/plugins/datepicker/datepicker.css') }}
    {{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
@endsection

@section('scripts')
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
                    <button type="button" class="btn btn-default m-t m-b m-l" onclick="createNewVoucher()">{{ trans('salon.create_new_voucher') }}</button>
                    <h5 class="text-muted m-l">{{ trans('salon.created_vouchers') }}</h5>
                    <table class="table table-working-hours">
                        <thead>
                        <tr>
                            <th class="text-center">{{ trans('salon.coupon') }}</th>
                            <th class="text-center">{{ trans('salon.discount_happy_hour') }}</th>
                            <th class="text-center">{{ trans('salon.vouchers_left') }}</th>
                            <th class="text-center">{{ trans('salon.expire_date') }}</th>
                            <th class="text-center">{{ trans('salon.promo_code') }}</th>
                            <th class="text-center">{{ trans('salon.action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($location->location_vouchers as $voucher)
                            <tr data-voucher="{{ $voucher->id }}">
                                <td class="text-center voucher-name">{{ $voucher->name }}</td>
                                <td class="text-center voucher-discount">{{ $voucher->discount }}</td>
                                <td class="text-center voucher-amount">{{ $voucher->amount }}</td>
                                <td class="text-center voucher-date">{{ $voucher->expire_date }}</td>
                                <td class="text-center voucher-code text-uppercase">{{ $voucher->code }}</td>
                                <td class="text-center">
                                    <a href="#" onclick="editVoucher({{ $voucher->id }})">
                                        <i class="fa fa-pencil table-profile"></i>
                                    </a>
                                    <a href="#" onclick="deleteVoucher({{ $voucher->id }})">
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
    </div>
    @include('partials.slowDayHour')
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