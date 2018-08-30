@extends('main')

@section('styles')
@endsection

@section('scripts')
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading pull-left">{{ trans('salon.my_profile') }}</h2>
        </div>
    </div>

    <div id="clientAppointments">
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection