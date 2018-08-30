@extends('main')

@section('styles')
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.min.js"></script>
@endsection

@section('scripts')
    {{ HTML::script('js/pos/posSaves.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading pull-left">{{ trans('salon.invoices') }}</h2>
        </div>
    </div>

    <div id="privacySettings">
        <div class="wrapper wrapper-content">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover d-table">
                        <thead>
                            <tr>
                                <th class="text-center">{{ trans('salon.payment_for') }}</th>
                                <th class="text-center">{{ trans('salon.booking_id') }}</th>
                                <th class="text-center">{{ trans('salon.client') }}</th>
                                <th class="text-center">{{ trans('salon.amount_charged') }}</th>
                                <th class="text-center">{{ trans('salon.payment_type') }}</th>
                                <th class="text-center">{{ trans('salon.invoice_date') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($invoices as $invoice)
                            <tr data-id="{{ $invoice['invoice_id'] }}">
                                <td class="text-center">{{ $invoice['payment_for'] }}</td>
                                <td class="text-center">{{ $invoice['booking_id'] }}</td>
                                <td class="text-center">{{ $invoice['client']['name'] }}</td>
                                <td class="text-center">{{ $invoice['amount_charged'] }}</td>
                                <td class="text-center">{{ $invoice['paid_with'] }}</td>
                                <td class="text-center">{{ $invoice['invoice_date'] }}</td>
                                <td class="text-center option-icons">
                                    <a href="#" onclick="viewInvoice({{ $invoice['invoice_id'] }})">
                                        <i class="fa fa-eye" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.view_invoice') }}"></i>
                                    </a>
                                    <a href="{{ route('printInvoice', $invoice['invoice_id']) }}" class="m-l">
                                        <i class="fa fa-file-pdf-o" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.print_pdf') }}"></i>
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
    @include('partials.payment.invoiceModal')
    <script>
    </script>
@endsection