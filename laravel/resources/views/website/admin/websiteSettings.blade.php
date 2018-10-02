@extends('main')

@section('styles')
{{ HTML::style('css/plugins/spectrum/spectrum.css') }}
{{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
@endsection

@section('scripts')
{{ HTML::script('js/plugins/dropzone/dropzone.js') }}
{{ HTML::script('js/plugins/spectrum/spectrum.js') }}
<script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=944zbcd6b70j3spki3txrzecsz6n99ua5dapocup4abxci3c"></script>
@endsection

@section('scripts-footer')
{{ HTML::script('js/plugins/jasny/jasny-bootstrap.min.js') }}
{{ HTML::script('js/website/websiteSettings.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12 website-options-heading">
            @if($salon->unique_url == null)
            <h2 class="text-center m-b">{{ trans('salon.miniweb_info') }}</h2>
            <small class="text-muted">{{ trans('salon.url_format') }}<strong>{{ trans('salon.your_salon_name') }}</strong></small>
            @endif
            <div class="input-group url-input">
                <input type="text" name="unique_url" class="form-control input-url" @if($salon->unique_url != null) value="{{ $salon->unique_url }}" @endif @if($salon->unique_url === null) placeholder="{{ trans('salon.your_salon_name') }}" @endif>
                <span class="input-group-btn">
                    <button id="setUrl" class="btn btn-default">{{ trans('salon.save_salon') }}</span>
                </span>
            </div>
            @if($salon->unique_url != null)
            <a href="{{ route('salonWebsite', $salon->unique_url) }}" class="website-url">{{ URL::to('/').'/'.$salon->unique_url }}</a>
            @endif
        </div>
    </div>
    
    <div id="websiteOptions" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li id="tab-1-li" class="active"><a data-toggle="tab" href="#tab-1">{{ trans('salon.main') }}</a></li>
                    <li id="tab-4-li" class=""><a data-toggle="tab" href="#tab-4">{{ trans('salon.social_interations') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <div class="ibox-content">
                                <h2 class="text-center m-b">{{ trans('salon.website_setup') }}</h2>
                                <hr>
                                <h4 class="text-left">{{ trans('salon.insert_content') }}</h4>
                                <div class="row" id="insertContent">
                                    {{ Form::open(array('id' => 'contentForm', 'class' => 'm-t m-l')) }}
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="form-group">
                                                <label for="companyIntroduction">{{ trans('salon.company_introduction') }}</label>
                                                {{ Form::textarea('company_introduction', isset($salon->website_content->company_introduction) ? $salon->website_content->company_introduction : null, array('id' => 'companyIntroduction', 'size' => '30x4', 'class' => 'form-control')) }}
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label for="websiteServiceText">{{ trans('salon.website_service_text') }}</label>
                                                {{ Form::textarea('website_service_text', isset($salon->website_content->website_service_text) ? $salon->website_content->website_service_text : null, array('id' => 'websiteServiceText', 'size' => '30x4', 'class' => 'form-control')) }}
                                            </div>
                                            <div class="form-group">
                                                <label class="block-label" for="displayPricing">{{ trans('salon.display_pricing_button') }}</label>
                                                <input type="checkbox" class="js-switch pricing-button" id="displayPricing" @if(isset($salon->website_content->display_pricing) && $salon->website_content->display_pricing === 1) checked @endif>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label for="websiteProductsText">{{ trans('salon.website_products_text') }}</label>
                                                {{ Form::textarea('website_product_text', isset($salon->website_content->website_products_text) ? $salon->website_content->website_products_text : null, array('id' => 'websiteProductsText', 'size' => '30x4', 'class' => 'form-control')) }}
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label for="websiteBookingText">{{ trans('salon.website_booking_text') }}</label>
                                                {{ Form::textarea('website_booking_text', isset($salon->website_content->website_booking_text) ? $salon->website_content->website_booking_text : null, array('id' => 'websiteBookingText', 'size' => '30x4', 'class' => 'form-control')) }}
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <label for="termsAndConditions">{{ trans('salon.terms_and_conditions') }}</label>
                                                {{ Form::textarea('terms_and_conditions', isset($salon->website_content->terms_and_conditions) ? $salon->website_content->terms_and_conditions : null, array('id' => 'termsAndConditions', 'size' => '30x4', 'class' => 'form-control')) }}
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <h4 class="text-left m-b-lg">{{ trans('salon.book_now_btn') }}</h4>
                                    <div class="form-group">
                                        <label class="block-label" for="activateBookingBtn">{{ trans('salon.display_booking_button') }}</label>
                                        <input type="checkbox" class="js-switch booking-button" id="activateBookingBtn" @if(isset($salon->website_content) && $salon->website_content->display_booking_btn != 0) checked @endif>
                                    </div>
                                    <div id="bookingButtonSection" @if(isset($salon->website_content) && $salon->website_content->display_booking_btn == 0) class="hidden" @endif>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="buttonText">{{ trans('salon.button_text') }}</label>
                                                <input type="text" @if(isset($salon->website_content->book_btn_text)) value="{{ $salon->website_content->book_btn_text }}" @endif id="buttonText" class="form-control">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label for="buttonBgColor">{{ trans('salon.button_bg_color') }}</label>
                                                <input type="text" @if(isset($salon->website_content->book_btn_bg)) value="{{$salon->website_content->book_btn_bg}}" @else value="#333" @endif id="spectrumBackground" name="button_bg">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="buttonTextColor">{{ trans('salon.button_text_color') }}</label>
                                                <input type="text" @if(isset($salon->website_content->book_btn_color)) value="{{$salon->website_content->book_btn_color}}" @else value="#fff" @endif id="spectrumText" name="button_text_color">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <button class="btn btn-success m-t m-b m-l" type="submit">{{ trans('salon.submit') }}</button>
                                    </div>
                                    
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="tab-4" class="tab-pane">
                        <div class="panel-body">
                            <div class="ibox-content">
                                <h3 class="text-muted">{{ trans('salon.social_links_desc') }}</h3>
                                {{ Form::open(array('id' => 'updateSocialLinks')) }}
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label for="facebookLink">Facebook</label>
                                            {{ Form::text('facebook_link', isset($salon->website_content->facebook_link) ? $salon->website_content->facebook_link : null, array('id' => 'facebookLink', 'class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label for="twitterLink">Twitter</label>
                                            {{ Form::text('twitter_link', isset($salon->website_content->twitter_link) ? $salon->website_content->twitter_link : null, array('id' => 'twitterLink', 'class' => 'form-control')) }}
                                        </div>
                                        <div class="form-group">
                                            <label for="instagramLink">Instagram</label>
                                            {{ Form::text('instagram_link', isset($salon->website_content->instagram_link) ? $salon->website_content->instagram_link : null, array('id' => 'instagramLink', 'class' => 'form-control')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t submit-socials">
                                    <button type="button" class="btn btn-success" onclick="updateSocialLinks({{ $salon->id }})">{{ trans('salon.submit') }}</button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
  
    <script>
        var button_info_required = '{{ trans('salon.button_info_required') }}';
        var updated_successfully = '{{ trans('salon.updated_successfuly') }}';
        var prompt = '{{ trans('salon.are_you_sure') }}';
        var about_image_route = '{{ route('saveAboutImage') }}';
        var slider_promo_route = '{{ route('updateSliderPromo') }}';
        var social_route = '{{ route('updateSocialLinks') }}';
        var slider_images = '{{ count($salon->slider_images) }}';
        var slider_promo_text = '{{ trans('salon.slider_promo') }}';

        $(document).ready(function() {
            
            $("#spectrumBackground").spectrum({
                color: @if(isset($salon->website_content->book_btn_bg)) '{{ $salon->website_content->book_btn_bg }}' @else '#333' @endif,
                preferredFormat: "hex",
                showInput: true,
                showPalette: true,
                palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
                change: function(color) {
                    $("#spectrumBackground").val(color.toHexString());
                }
            });
            
            $("#spectrumText").spectrum({
                color: @if(isset($salon->website_content->book_btn_color)) '{{ $salon->website_content->book_btn_color }}' @else '#fff' @endif,
                preferredFormat: "hex",
                showInput: true,
                showPalette: true,
                palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
                change: function(color) {
                    $("#spectrumText").val(color.toHexString());
                }
            });
            
        });

        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });
        
        Dropzone.options.sliderDropzone = {

            autoProcessQueue: false,
    
            init: function() {
                var submitButton = document.querySelector("#dropzoneSubmit")
                    imagesDropzone = this; // closure
        
                submitButton.addEventListener("click", function() {
                    imagesDropzone.processQueue();
                });
                
                this.on('success', function() {
                   imagesDropzone.options.autoProcessQueue = true;
                });
                
                this.on('error', function(file, message, xhr) {
                    var header = xhr.responseText;
                    toastr.error(header);
                    this.removeFile(file);
                });
                
                this.on('complete', function (message) {
                    if(this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
                        toastr.success(updated_successfully);
                    }
                });
        
            }
        };

    </script>
@endsection

 