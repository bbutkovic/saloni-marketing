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
                    <li id="tab-2-li" class=""><a data-toggle="tab" href="#tab-2">{{ trans('salon.website_images') }}</a></li>
                    <li id="tab-3-li" class=""><a data-toggle="tab" href="#tab-3">{{ trans('salon.slider_promotions') }}</a></li>
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
                                        <div class="form-group">
                                            <label for="companyIntroduction">{{ trans('salon.company_introduction') }}</label>
                                            {{ Form::textarea('company_introduction', isset($salon->website_content->company_introduction) ? $salon->website_content->company_introduction : null, array('id' => 'companyIntroduction', 'size' => '30x4', 'class' => 'form-control', 'required')) }}
                                        </div>
                                        <div class="form-group">
                                            <label for="websiteServiceText">{{ trans('salon.website_service_text') }}</label>
                                            {{ Form::textarea('website_service_text', isset($salon->website_content->website_service_text) ? $salon->website_content->website_service_text : null, array('id' => 'websiteServiceText', 'size' => '30x4', 'class' => 'form-control', 'required')) }}
                                        </div>
                                        <div class="form-group">
                                            <label for="websiteBookingText">{{ trans('salon.website_booking_text') }}</label>
                                            {{ Form::textarea('website_booking_text', isset($salon->website_content->website_booking_text) ? $salon->website_content->website_booking_text : null, array('id' => 'websiteBookingText', 'size' => '30x4', 'class' => 'form-control', 'required')) }}
                                        </div>
                                        <div class="form-group">
                                            <label for="websiteAboutText">{{ trans('salon.website_about_text') }}</label>
                                            {{ Form::textarea('website_about_text', isset($salon->website_content->website_about_text) ? $salon->website_content->website_about_text : null, array('id' => 'websiteAboutText', 'size' => '30x4', 'class' => 'form-control', 'required')) }}
                                        </div>
                                        <div class="form-group">
                                            <label for="termsAndConditions">{{ trans('salon.terms_and_conditions') }}</label>
                                            {{ Form::textarea('terms_and_conditions', isset($salon->website_content->terms_and_conditions) ? $salon->website_content->terms_and_conditions : null, array('id' => 'termsAndConditions', 'size' => '30x4', 'class' => 'form-control', 'required')) }}
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <h4 class="text-left m-b-lg">{{ trans('salon.book_now_btn') }}</h4>
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
                                    <div class="row">
                                        <button class="btn btn-success m-t m-b m-l" type="submit">{{ trans('salon.submit') }}</button>
                                    </div>
                                    
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div class="ibox-content">
                                <div class="about-image m-b-lg">
                                    <h3 class="text-muted">{{ trans('salon.about_image') }}</h3>
                                    @if(!isset($salon->website_content) || $salon->website_content->about_image === null)
                                        <div class="about-image-placeholder m-b"></div>
                                    @elseif(isset($salon->website_content) && $salon->website_content->about_image != null)
                                        <div class="about-salon-image m-b" style="background-image: url('{{ URL::to('/').'/images/salon-websites/about-image/'.$salon->website_content->about_image }}')"></div>
                                    @endif
                                    {{ Form::open(array('files' => 'true')) }}
                                    <div class="form-group">
                                        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                            <span class="input-group-addon btn btn-default btn-file">
                                                <span class="fileinput-new">{{ trans('salon.about_image') }}</span>
                                                <input type="file" name="about_image" id="aboutImageUpload">
                                            </span>
                                            <div class="form-control" data-trigger="fileinput">
                                                <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                <span class="fileinput-filename">@if(isset($salon->website_content->about_image)) {{ $salon->website_content->about_image }} @endif</span>
                                            </div>
                                        </div> 
                                    </div>
                                    <button type="button" class="btn btn-success" onclick="saveAboutImage()">{{ trans('salon.submit_about_image') }}</button>
                                    {{ Form::close() }}
                                </div>
                                <div class="slider-images">
                                    <h3 class="text-muted">{{ trans('salon.slider_images') }}</h3>
                                    <small>{{ trans('salon.available_images') }}. {{ trans('salon.slider_images_desc') }}</small>
                                    <div class="uploaded-images">
                                        @foreach($global_images as $web_image_global)
                                            <div class="location-photo slider-image text-center" id="webImage{{$web_image_global->id}}">
                                                <div class="photo-container" style="background-image: url({{ URL::to('/').'/images/salon-websites/slider-images/'.$web_image_global->image_name }})"></div>
                                                <input name="slider_image" type="checkbox" class="select-global-image" data-id="{{ $web_image_global->id }}" @if(isset($slider_arr[$web_image_global->id])) checked @endif>
                                            </div>
                                        @endforeach

                                        @foreach($salon->website_images as $web_image)
                                        <div class="location-photo slider-image text-center" id="webImage{{$web_image->id}}">
                                            <div class="photo-container" style="background-image: url({{ URL::to('/').'/images/salon-websites/slider-images/'.$web_image->image_name }})"></div>
                                            <input name="slider_image" type="checkbox" class="select-global-image" data-id="{{ $web_image->id }}" @if(isset($slider_arr[$web_image->id])) checked @endif>
                                            <a href="#" class="delete-photo delete-slider-image" onclick="deleteSliderImage({{ $web_image->id }})"><i class="fa fa-trash"></i></a>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="upload-images">
                                        <form action="{{ route('uploadSliderImages') }}" class="dropzone" enctype="multipart/form-data" id="sliderDropzone">
                                            {{ csrf_field() }}
                                            <div class="fallback">
                                                <input type="file" name="file" multiple>
                                            </div>
                                        </form>
                                    </div>
                                    <button type="button" class="btn btn-success m-t" id="dropzoneSubmit">{{ trans('salon.submit_slider_images') }}</button>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    
                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body">
                            <div class="ibox-content">
                                <h3 class="text-muted">{{ trans('salon.slider_promo_desc') }}</h3>
                                @if(!$salon->website_images->isNotEmpty())
                                    <h5>{{ trans('salon.no_slider_images') }}</h5>
                                @endif
                                <hr>
                                {{ Form::open(array('id' => 'sliderPromoForm')) }}
                                    @for($i = 1; $i <= count($salon->slider_images); $i++)
                                    <?php $index = $i-1; ?>
                                    <div class="col-lg-6 slider-content-container">
                                        <h3 class="text-muted">{{ trans('salon.slider_promo') . ' ' . $i }}</h3>
                                        <div class="form-group">
                                            <label class="block-label" for="activateSlide{{ $i }}">{{ trans('salon.show_slide') }}</label>
                                            <input type="checkbox" class="js-switch slider-active" id="activateSlide{{ $i }}" @if(isset($salon->slider_promos[$index]) && $salon->slider_promos[$index]->active) checked @endif>
                                        </div>
                                        <div class="form-group">
                                            <label for="sliderTitle{{$i}}">{{ trans('salon.slider_promo_title') }}</label>
                                            <input name="slider_title_{{$i}}" @if(isset($salon->slider_promos[$index])) value="{{ $salon->slider_promos[$index]->title }}" @endif class="form-control slider-title" id="sliderTitle{{$i}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="sliderText{{$i}}">{{ trans('salon.slider_promo_text') }}</label>
                                            <input name="slider_text_{{$i}}" @if(isset($salon->slider_promos[$index])) value="{{ $salon->slider_promos[$index]->text }}" @endif class="form-control slider-text" id="sliderText{{$i}}">
                                        </div>
                                        <div class="form-group">
                                            <label for="sliderButton{{$i}}">{{ trans('salon.slider_booking_btn') }}</label>
                                            <input type="checkbox" name="book_btn_{{$i}}" @if(isset($salon->slider_promos[$index]) && $salon->slider_promos[$index]->include_btn === 1) checked @endif class="book-now-checkbox">
                                        </div>
                                    </div>
                                    @endfor
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-success">{{ trans('salon.update') }}</button>
                                    </div>
                                {{ Form::close() }}
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
                                        <div class="form-group">
                                            <label for="pinterestLink">Pinterest</label>
                                            {{ Form::text('pinterest_link', isset($salon->website_content->pinterest_link) ? $salon->website_content->pinterest_link : null, array('id' => 'pinterestLink', 'class' => 'form-control')) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t submit-socials">
                                    <button type="button" class="btn btn-success" onclick="updateSocialLinks({{ $salon->id }})">{{ trans('salon.update') }}</button>
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
    
        var updated_successfully = '{{ trans('salon.updated_successfuly') }}';
        var prompt = '{{ trans('salon.are_you_sure') }}';
        var about_image_route = '{{ route('saveAboutImage') }}';
        var slider_promo_route = '{{ route('updateSliderPromo') }}';
        var social_route = '{{ route('updateSocialLinks') }}';
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

 