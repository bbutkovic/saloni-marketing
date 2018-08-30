@extends('main')

@section('styles')
    {{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
@endsection

@section('scripts')
    {{ HTML::script('js/plugins/jasny/jasny-bootstrap.min.js') }}
    {{ HTML::script('js/pos/posSaves.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading pull-left">Fiskalizacija</h2>
        </div>
    </div>

    <div id="clientAppointments">
        <div class="wrapper wrapper-content">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    @if(!isset($location->billing_info))
                    <div class="billing-info-notice text-center">
                        <h4 class="text-muted">Za pravilno funkcioniranje fiskalne blagajne unesite informacije za naplatu</h4>
                        <a href="{{ route('billingInfo') }}"><button class="btn btn-default">Informacije za naplatu</button></a>
                    </div>
                    @endif
                    {{ Form::open(array('route' => 'updateFiskalSettings', 'id' => 'addCertificate', 'files' => true)) }}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="location_photo">Fiskalni certifikat</label>
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                            <span class="fileinput-filename">@if(isset($salon->fiskal_certificate->certificate_name)){{ $salon->fiskal_certificate->certificate_name }}@endif</span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file">
                                            <span class="fileinput-new">Dodaj</span>
                                            <input type="file" name="fiskalCertificate" id="certificateDocument">
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group ">
                                    <label for="certificatePassword">Lozinka</label>
                                    <input class="form-control" id="certificate-password" required name="password" type="text" @if(isset($salon->fiskal_certificate->certificate_name)) value="{{ $salon->fiskal_certificate->password }}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="row text-center m-t">
                            <button type="submit" class="btn btn-success">{{ trans('salon.submit') }}</button>
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