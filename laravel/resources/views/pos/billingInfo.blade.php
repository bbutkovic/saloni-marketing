@extends('main')

@section('styles')
@endsection

@section('scripts')
    {{ HTML::script('js/pos/posSaves.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading pull-left">{{ trans('salon.billing_info') }}</h2>
        </div>
    </div>

    <div id="clientAppointments">
        <div class="wrapper wrapper-content">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    {{ Form::open(array('class' => 'm-t')) }}
                    <div class="row">
                        <div class="col-lg-6">
                            {{ Form::hidden('site_location', 'location', array('id' => 'siteLocation')) }}
                            {{ Form::hidden('input_id', $location->id, array('id' => 'inputId')) }}
                            <div class="form-group">
                                <label for="billingAddress">{{ trans('salon.billing_address') }}*</label>
                                {{ Form::text('billing_address', isset($location->billing_info->address) ? $location->billing_info->address : $location->address, array('id' => 'billingAddress', 'class' => 'form-control')) }}
                                <small class="text-danger">{{ $errors->first('billing_address') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="billingCity">{{ trans('salon.salon_city') }}*</label>
                                {{ Form::text('billing_city', isset($location->billing_info->city) ? $location->billing_info->city : $location->city, array('id' => 'billingCity', 'class' => 'form-control')) }}
                                <small class="text-danger">{{ $errors->first('billing_city') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="billingZip">{{ trans('salon.salon_zip') }}*</label>
                                {{ Form::text('billing_zip', isset($location->billing_info->zip) ? $location->billing_info->zip : $location->zip, array('id' => 'billingZip', 'class' => 'form-control')) }}
                                <small class="text-danger">{{ $errors->first('billing_zip') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="billingCountry">{{ trans('salon.salon_country') }}*</label>
                                <select name="billing_country" id="billingCountry" class="form-control">
                                    @foreach($countries as $country)
                                        <option value="{{ $country->country_identifier }}" @if(isset($location->billing_info->country) && ($location->billing_info->country == $country->country_identifier)) ? selected : null @endif >{{ $country->country_local_name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger">{{ $errors->first('billing_country') }}</small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            @if($location->salon->country == 'hr')
                            <div class="form-group">
                                <label for="billingOib">{{ trans('salon.billing_oib') }}</label>
                                {{ Form::text('billing_oib', isset($location->billing_info->oib) ? $location->billing_info->oib : null, array('id' => 'billingOib', 'class' => 'form-control', 'required')) }}
                                <small class="text-danger">{{ $errors->first('billing_oib') }}</small>
                            </div>
                            @endif
                            <div class="form-group">
                                <label for="billingIban">IBAN</label>
                                {{ Form::text('billing_iban', isset($location->billing_info->iban) ? $location->billing_info->iban : null, array('id' => 'billingIban', 'class' => 'form-control')) }}
                                <small class="text-danger">{{ $errors->first('billing_iban') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="billingSwift">SWIFT</label>
                                {{ Form::text('billing_swift', isset($location->billing_info->swift) ? $location->billing_info->swift : null, array('id' => 'billingSwift', 'class' => 'form-control')) }}
                                <small class="text-danger">{{ $errors->first('billing_swift') }}</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="locationLabel">Oznaka poslovnice</label>
                                {{ Form::text('location_label', $location->billing_info->location_label, array('id' => 'locationLabel', 'class' => 'form-control', 'required')) }}
                            </div>
                            <div class="form-group">
                                <label for="parking">Poslovnica u sustavu PDV-a: </label>
                                <div class="radio radio-info radio-inline">
                                    <input type="radio" id="pdv1" value="1" name="pdv_sustav" @if($location->billing_info->pdv_sustav === 1) checked @endif>
                                    <label for="pdv1">{{ trans('salon.radio_yes') }}</label>
                                </div>
                                <div class="radio radio-inline">
                                    <input type="radio" id="pdv2" value="0" name="pdv_sustav">
                                    <label for="pdv2">{{ trans('salon.radio_no') }}</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="oznakaSlijednosti">Oznaka slijednosti <i class="fa fa-info-circle" aria-hidden="true"
                                                                                 data-toggle="tooltip" data-placement="top" title=""
                                                                                 data-original-title="Ako je odabrano Na nivou poslovnog prostora, svaka poslovnica će imati svoju numeraciju, neovisno o naplatnom uređaju (npr. 1/1/1, 1/1/2). Ako je odabrano Na nivou naplatnog uređaja, svaki naplatni uređaj će imati svoju numeraciju, neovisno o poslovnici (1/1/1, 1/2/1)"></i></label>
                                {{ Form::select('oznaka_slijednosti', ['T' => 'Na nivou poslovnog prostora', 'F' => 'Na nivou naplatnog uređaja'], $location->billing_info->slijednost, array('id' => 'oznakaSlijednosti', 'class' => 'form-control', 'required')) }}
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <button type="button" class="btn btn-success m-l" onclick="submitBillingInfo()">{{ trans('salon.save_salon') }}</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        var billing_info_route = '{{ route('postBillingInfo') }}';
    </script>
@endsection