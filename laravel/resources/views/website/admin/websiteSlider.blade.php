@extends('main')

@section('styles')
@endsection

@section('scripts')
    {{ HTML::script('js/plugins/dropzone/dropzone.js') }}
    {{ HTML::script('js/website/slider.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12 website-options-heading">
            <h1>{{ trans('salon.homepage_images') }}</h1>
        </div>
    </div>

    <div id="websiteOptions" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="ibox-content">
                <div class="slider-images">
                    <h3 class="text-muted">{{ trans('salon.slider_images') }}</h3>
                    <small class="text-muted">{{ trans('salon.slider_images_desc') }}</small>
                    <div class="uploaded-images">
                        @foreach($images as $web_image)
                            <div class="location-photo slider-image" id="webImage{{$web_image->id}}">
                                <div class="photo-container" style="background-image: url({{ URL::to('/').'/images/salon-websites/slider-images/'.$web_image->image_name }})"></div>
                                <a href="#" class="delete-photo" onclick="deleteSliderImage({{ $web_image->id }})"><i class="fa fa-trash"></i></a>
                            </div>
                        @endforeach
                    </div>
                    <div class="upload-images">
                        <form action="{{ route('uploadSliderGlobalImages') }}" class="dropzone" enctype="multipart/form-data" id="sliderDropzone">
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
        var slider_promo_route = '{{ route('updateSliderPromo') }}';

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