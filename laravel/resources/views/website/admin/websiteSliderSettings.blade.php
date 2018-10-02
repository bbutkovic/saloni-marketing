@extends('main')

@section('styles')
    {{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
@endsection

@section('scripts')
    {{ HTML::script('js/plugins/dropzone/dropzone.js') }}
@endsection

@section('scripts-footer')
    {{ HTML::script('js/plugins/jasny/jasny-bootstrap.min.js') }}
    {{ HTML::script('js/website/websiteSettings.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12 website-options-heading">
            <h2 class="text-center m-b">{{ trans('salon.website_images') }}</h2>
        </div>
    </div>

    <div id="websiteOptions" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="ibox-content">
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

    <script>
        var updated_successfully = '{{ trans('salon.updated_successfuly') }}';
        var prompt = '{{ trans('salon.are_you_sure') }}';
        var slider_images = '{{ count($salon->slider_images) }}';

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