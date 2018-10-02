@extends('main')

@section('styles')
    {{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
    {{ HTML::style('css/plugins/ionslider/ion.rangeSlider.css') }}
    {{ HTML::style('css/plugins/ionslider/ion.rangeSlider.skinFlat.css') }}
    {{ HTML::style('css/plugins/datepicker/datepicker.css') }}
@endsection

@section('scripts')

    {{ HTML::script('js/plugins/ionslider/ion.rangeSlider.min.js') }}
    {{ HTML::script('js/plugins/jasny/jasny-bootstrap.min.js') }}
    {{ HTML::script('js/plugins/datepicker/datepicker.js') }}
@endsection

<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId            : '387717418355900',
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v3.1'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

@section('scripts-footer')
    {{ HTML::script('js/campaigns/facebook.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading pull-left">Facebook Marketing</h2>
        </div>
        <a href="https://www.facebook.com/business/products/ads" class="m-l">Facebook marketing info</a>
    </div>

    <div id="facebookCampaigns" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li id="tab-1-li" class=""><a data-toggle="tab" href="#tab-1">{{ trans('salon.campaign_list') }}</a></li>
                    <li id="tab-2-li" class="active"><a data-toggle="tab" href="#tab-2">{{ trans('salon.add_new_campaign') }}</a></li>
                </ul>
                <div class="tab-content">

                    <div id="tab-1" class="tab-pane">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-2" class="tab-pane active">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-content">
                                        {{ Form::open(array('id' => 'newFacebookAdCampaign')) }}
                                            <div class="row">
                                                {{--facebook campaign setup--}}
                                                <div class="col-lg-6">
                                                    <h2 class="text-muted">{{ trans('salon.campaign_details') }}</h2>
                                                    <div class="form-group">
                                                        <label for="campaignName">{{ trans('salon.campaign_name') }}</label>
                                                        {{ Form::text('campaign_name', null, array('id' => 'campaignName', 'class' => 'form-control')) }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="campaignObjective">{{ trans('salon.campaign_objective') }}</label>
                                                        {{ Form::select('campaign_objective', ['BRAND_AWARENESS' => trans('salon.brand_awareness'), 'LINK_CLICKS' => trans('salon.link_clicks'), 'PAGE_LIKES' => trans('salon.page_likes')], 'LINK_CLICKS', array('id' => 'campaignObjective', 'class' => 'form-control')) }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="campaignStatus">{{ trans('salon.campaign_status') }}</label>
                                                        {{ Form::select('campaign_status', ['ACTIVE' => trans('salon.campaign_active'), 'PAUSED' => trans('salon.campaign_paused')], 'PAUSED', array('id' => 'campaignStatus', 'class' => 'form-control')) }}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <h2 class="text-muted">{{ trans('salon.adset_details') }}</h2>
                                                    <div class="form-group">
                                                        <label for="adSetName">{{ trans('salon.adset_name') }}</label>
                                                        {{ Form::text('adset_name', null, array('id' => 'adSetName', 'class' => 'form-control')) }}
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="campaignStart">{{ trans('salon.campaign_start') }}</label>
                                                                {{ Form::text('campaign_start', null, array('id' => 'campaignStart', 'class' => 'form-control')) }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="campaignEnd">{{ trans('salon.campaign_end') }}</label>
                                                                {{ Form::text('campaign_end', null, array('id' => 'campaignEnd', 'class' => 'form-control')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="campaignBudget">{{ trans('salon.campaign_budget') }} $ (USD)</label>
                                                                {{ Form::text('campaign_budget', 10, array('id' => 'campaignBudget', 'class' => 'form-control')) }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="campaignBids">{{ trans('salon.campaign_bids') }} $ (USD)</label>
                                                                {{ Form::text('campaign_bids', 0.2, array('id' => 'campaignBids', 'class' => 'form-control')) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <h2 class="text-muted">{{ trans('salon.campaign_audience') }}</h2>
                                                    <small class="text-danger">{{ trans('salon.audience_location_info') }}</small>
                                                    <div class="form-group">
                                                        <label for="audienceCountries">{{ trans('salon.audience_countries') }} <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('salon.audience_countries_desc') }}" data-original-title=""></i></label>
                                                        {{ Form::text('audience_countries', null, array('id' => 'audienceCountries', 'class' => 'form-control')) }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="audienceCities">{{ trans('salon.audience_cities') }} <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('salon.audience_cities_desc') }}" data-original-title=""></i></label>
                                                        {{ Form::text('audience_cities', null, array('id' => 'audienceCities', 'class' => 'form-control')) }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="audienceMaxAge">{{ trans('salon.audience_age_range') }}</label>
                                                        {{ Form::text('audience_age_range', null, array('id' => 'campaignAgeRange', 'class' => 'form-control')) }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="audienceInterests">{{ trans('salon.audience_interests') }}  <i class="fa fa-info-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{ trans('salon.audience_interests_desc') }}" data-original-title=""></i></label>
                                                        {{ Form::text('audience_interests', null, array('id' => 'audienceInterests', 'class' => 'form-control')) }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="audienceGender">{{ trans('salon.audience_gender') }}</label>
                                                        {{ Form::select('audience_gender', [1 => trans('salon.male'), 2 => trans('salon.female'), '1, 2' => trans('salon.male_female')], '1, 2', array('id' => 'audienceGender', 'class' => 'form-control', 'required')) }}
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <h2 class="text-muted">{{ trans('salon.ad_design') }}</h2>
                                                    <div class="form-group">
                                                        <label for="adImage">{{ trans('salon.ad_image') }}</label>
                                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                            <span class="input-group-addon btn btn-default btn-file">
                                                                <span class="fileinput-new">{{ trans('salon.select_ad_image') }}</span>
                                                                <input type="file" name="ad_image" id="adImage">
                                                            </span>
                                                            <div class="form-control" data-trigger="fileinput">
                                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                                <span class="fileinput-filename"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="facebookPageID">{{ trans('salon.facebook_page_id') }}</label>
                                                        <a href="https://www.labnol.org/internet/find-facebook-page-id-profile/6909/">{{ trans('salon.how_to_get_pageid') }}</a>
                                                        {{ Form::text('facebook_page_id', null, array('id' => 'facebookPageID', 'class' => 'form-control', 'required')) }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="redirectLink">{{ trans('salon.redirect_link') }}</label>
                                                        {{ Form::text('redirect_link', URL::to('/') . '/' . $salon->unique_url, array('id' => 'redirectLink', 'class' => 'form-control', 'required')) }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="adTitle">{{ trans('salon.ad_title') }}</label>
                                                        {{ Form::text('ad_title', null, array('id' => 'adTitle', 'class' => 'form-control', 'required')) }}
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="adMsg">{{ trans('salon.ad_message') }}</label>
                                                        {{ Form::text('ad_message', null, array('id' => 'adMsg', 'class' => 'form-control', 'required')) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row text-center">
                                                <button class="btn btn-success m-t">{{ trans('salon.create_campaign') }}</button>
                                            </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    var trans_age = '{{ trans('salon.age') }}';
</script>