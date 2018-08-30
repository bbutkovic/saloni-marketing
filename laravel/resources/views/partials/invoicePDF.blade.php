<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <title>Invoice</title>
    <style>
        * {font-family: 'DejaVu Sans', Arial, Helvetica, sans-serif;}
        p {font-size: 10px;}
        th {font-size: 10px;}
        td {font-size: 10px;}
        #company {position: absolute; left: 0; top: -40px; right: 0; height: 90px; width: 720px; font-size: 14px;}
        #company2 {position: absolute; left: 0; top: 40px; right: 0; height: 115px; width: 390px; font-size: 14px;}
        #client {position: absolute; left: 0; top: 120px; right: 0; width: 340px; border: 1px solid #000; padding:3px;}
        #bill {position: absolute; left: 410px; top: -40px; right: 0; width: 310px; font-size: 13px;}
        #content {margin-top: 310px; display: block;}
    </style>
</head>
<body>

<div id="company">
    <p>
        <span style="font-weight: bold; font-size:16px;">{{ $data['location']->location_name }}</span><br>
        {{ $data['location']->address.', '.$data['location']->zip.' '.$data['location']->city }}<br>
        {{ trans('salon.phone').': '.$data['location']->business_phone.', '.trans('salon.email').': '.$data['location']->email }}<br>
    </p>
</div>
<div id="company2">
    <p style="font-size:10px;">
        OIB: {{ $data['location']['billing_info']->oib }},
        {{--{{ trans('main.tax_id').': HR'.$data['company']->oib }}--}}
        <br>
        {{--{{ trans('main.bank_account').': '.$data['company']->bank_account }}<br>--}}
        {{ 'IBAN: '.$data['location']['billing_info']->iban.', SWIFT: '.$data['location']['billing_info']->swift }}
    </p>
</div>

@if (isset($data['client']))
    <div id="client">
        <p style="padding: 0 0 0 10px; margin: 0; font-weight: bold; font-size: 12px;">
            {{ $data['client']['name'] }}
        </p>
        <p style="padding: 4px 0 0 10px; margin: 0; font-size: 12px;">
            @if ($data['client']['address'])
                {{ $data['client']['address'] }}<br/>
            @endif
            {{ $data['client']['zip'] }}
            @if ($data['client']['city'])
                {{ $data['client']['city'] }}
                <br>
            @endif
        </p>
    </div>
@endif

<div id="bill">
    {{--<p style="font-size: 16px;"><b>{{ $data['invoice']->invoice_no_text.' &nbsp;'.$data['invoice_id'] }}</b></p>

    @if ($data['invoice']->reversed == 'T')
        <p style="font-size: 13px;">{{ trans('main.reversed_invoice_no').': &nbsp;'.$data['invoice']->reversed_id }}</p>
    @endif--}}
    <p style="font-size: 16px;"><b>{{ trans('salon.invoice_no').' &nbsp;'.$data['invoice_id'] }}</b></p>

    <p>
        {{ trans('salon.invoice_date').': '.$data['invoice_date'] }}<br>
        {{ trans('salon.document_location').': '.$data['location']['city'] }}<br>
        {{ trans('salon.payment_type').': '.$data['paid_with'] }}
    </p>
</div>
<div id="content">
    <table cellspacing="0" style="border: 1px solid black; width: 100%;">
        <thead>
        <tr align="left">
            <th style="border-bottom: 2px solid black; text-align:center">{{ trans('salon.rb') }}</th>
            <th style="border-bottom: 2px solid black">{{ trans('salon.code') }}</th>
            <th style="border-bottom: 2px solid black">{{ trans('salon.sr_name') }}</th>
            <th style="border-bottom: 2px solid black; text-align:center">{{ trans('salon.quantity') }}</th>
            <th style="border-bottom: 2px solid black; text-align:center">{{ trans('salon.price') }}</th>
            <th style="border-bottom: 2px solid black; text-align:center">{{ trans('salon.sr_tax') }} %</th>
            <th style="border-bottom: 2px solid black; text-align:center">{{ trans('salon.rebate') }}</th>
            <th style="border-bottom: 2px solid black; text-align:center">{{ trans('salon.rebate_price') }}</th>
            <th style="border-bottom: 2px solid black; text-align:center">{{ trans('salon.total_price') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($data['services'] as $key=>$service)
            <tr>
                <td style="text-align:center">{{ $key+1 }}</td><td>{{ $service['code'] }}</td>
                <td>{{ $service['name'] }}</td>
                <td style="text-align:center">1</td>
                <td style="text-align:center">{{ $service['price_no_vat'] }}</td>
                <td style="text-align:center">{{ $service['vat'] }}</td>
                <td style="text-align:center">@if($service['service_discounted'] == '1') 100% @else 0 @endif</td>
                <td style="text-align:center">@if($service['service_discounted'] == '1') {{ $service['price_no_vat'] }} @else 0 @endif</td>
                <td style="text-align:right">@if($service['service_discounted'] == '1') 0 @else {{ $service['total_price'] }} @endif</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table style="page-break-inside: avoid; width: 100%; margin-top: 12px; margin-bottom: 17px">
        <tr>
            <td width="100px" style="text-align:right;padding-right: 15px;padding-left: 420px;font-size: 11px">
                {{ trans('salon.sum') }}:
            </td>
            <td width="100px" style="text-align:right;font-size: 11px">{{ $data['total_no_vat'] }}</td>
        </tr>
        <tr>
            <td width="100px" style="text-align:right;padding-right: 15px;padding-left: 420px;font-size: 11px">
                {{ trans('salon.sum_w_vat') }}:
            </td>
            <td width="100px" style="text-align:right;font-size: 11px">{{ $data['price_w_vat'] }}</td>
        </tr>
        <tr>
            <td width="100px" style="text-align:right;padding-right: 15px;padding-left: 420px;font-size: 11px">
                {{ trans('salon.rebate') }}:
            </td>
            <td width="100px" style="text-align:right;font-size: 11px">@if($data['applied_discount'] === 1) {{ $data['discount_amount'] }}% @else 0 @endif</td>
        </tr>
        <tr>
            <td width="100px" style="text-align:right;padding-right: 15px;padding-left: 420px;font-size: 11px">
                {{ trans('salon.rebate_price') }}:
            </td>
            <td width="100px" style="text-align:right;font-size: 11px">@if($data['applied_discount'] === 1) {{ $data['amount_charged'] }} @else 0 @endif</td>
        </tr>
        <tr>
            <td width="100px" style="text-align:right;padding-right: 15px;padding-left: 420px;font-size: 11px">
                {{ trans('salon.voucher_used') }}:
            </td>
            <td width="100px" style="text-align:right;font-size: 11px">{{ $data['voucher_used'] }}</td>
        </tr>

        {{--@if ($data['company']->pdv_user == 'T')
            @foreach ($data['tax_array'] as $tax)
                <tr>
                    <td width="100px" style="text-align:right;padding-right: 15px;padding-left: 420px;font-size: 11px">
                        {{ trans('main.tax').' ('.$tax['tax'].'%)' }}:
                    </td>
                    <td width="100px" style="text-align:right;font-size: 11px">{{ $tax['sum'] }}</td>
                </tr>
            @endforeach
        @endif--}}
    <!-- type 2 - different currencies -->

        <tr>
            <td width="150px" style="text-align:right;padding-right: 15px;padding-left: 420px;font-size: 13px">
                <span style="font-weight: bold;">{{ trans('salon.total_price') }}</span>
            </td>
            <td width="100px" style="text-align:right;font-size: 13px;font-weight: bold;">
                {{ $data['amount_charged'] }}
            </td>
        </tr>

    </table>

    {{--@if ($data['invoice']->retail == 'F' && $data['invoice']->show_model == 'T' && $data['invoice']->model != '')
        <p>{{ trans('main.model').': '.$data['invoice']->model.', '.trans('main.reference_number').': '.
            $data['invoice']->reference_number }}
        </p>
    @endif

    @if ($data['invoice']->contract_id && $data['invoice']->create_after_end == 'F')
        @if ($data['invoice']->current_contract_invoice <= $data['invoice']->number_of_invoices)
            <p>{{ $data['invoice']->current_contract_invoice.'/'.$data['invoice']->number_of_invoices }}</p>
        @endif
    @endif
<!--
    @if ($data['invoice']->client && $data['invoice']->client->int_client == 'T' && $data['invoice']->retail == 'F')
        @if ($data['type'] == 1 && $data['invoice']->language_id != 1)
        <p>VAT does not apply by the article 17 point 1. VAT law</p>
@else
        <p>Ne podliježe obračunu PDV-a prema Čl.17. st. 1. Zakona o PDV-u</p>
@endif
    @endif
        -->
    @if ($data['company']->pdv_user == 'F')
        <p>Obveznik nije u sustavu PDV-a, PDV nije obračunat temeljem čl. 90. stavka 2. Zakona o PDV-u.</p>
    @else
        @foreach ($data['tax_notes_array'] as $note)
            <p>{{ $note }}</p>
        @endforeach
    @endif

<!-- type 2 -->
    @if ($data['type'] == 2 || ($data['type'] == 1 && $data['invoice']->language_id == 1))
        @if ($data['invoice']->note)
            <p>{{ $data['invoice']->note }}</p>
        @endif

        @foreach ($data['invoice']->notes as $note)
            <p>{{ $note->note }}</p>
        @endforeach

        @if ($data['company']->payment_terms)
            <p><strong>{{ trans('main.payment_terms') }}: </strong>{{ $data['company']->payment_terms }}</p>
        @endif

        @if ($data['company']->general_terms)
            <p><strong>{{ trans('main.general_terms') }}: </strong>{{ $data['company']->general_terms }}</p>
        @endif
    <!-- type 1 - different languages -->
    @else
        @if ($data['invoice']->int_note)
            <p>{{ $data['invoice']->int_note }}</p>
        @endif
    @endif

    @if ($data['invoice']->zki)
        <p>ZKI: {{ $data['invoice']->zki }}</p>
    @endif

    @if ($data['invoice']->jir)
        <p>JIR: {{ $data['invoice']->jir }}</p>
    @endif

    @if ($data['company']->logo2)
    <!--<div id="logo2"><img src="{{ $data['invoice']->logo2_url }}"></div>-->
    @endif

    <br>
    <table style="page-break-inside: avoid;">
        @if ($data['invoice']->retail == 'F')
            <tr>
                <td width="100px" style="padding-right: 25px;padding-left: 220px;">
                    {{ trans('main.for').' '.$data['company']->name }}:
                </td>
                <td width="100px" style="padding-left: 90px">
                    {{ trans('main.operator').' - '.trans('main.reviewer') }}
                </td>
            </tr>
            <tr>
                @if ($data['company']->legal_form == 1)
                    <td width="100px" style="padding-right: 25px;padding-left: 220px;">
                        {{ trans('main.director').': '.$data['admin'] }}
                    </td>
                @else
                    <td width="100px" style="padding-right: 25px;padding-left: 220px;">
                        {{ trans('main.owner').': '.$data['admin'] }}
                    </td>
                @endif

                <td width="100px" style="padding-left: 90px">
                    {{ $data['invoice']->user->first_name.' '.$data['invoice']->user->last_name }}
                </td>
            </tr>
        @else
            <tr>
                <td width="100px" style="padding-left: 463px">
                    {{ trans('main.operator').' - '.trans('main.reviewer') }}
                </td>
            </tr>
            <tr>
                <td width="100px" style="padding-left: 463px">
                    {{ $data['invoice']->user->first_name.' '.$data['invoice']->user->last_name }}
                </td>
            </tr>
        @endif
    </table>
</div><br/><br/>
<div id="footer">
    <div id="footer_text"><p>{{ $data['company']->document_footer }}</p></div>--}}
</div>
</body>
</html>