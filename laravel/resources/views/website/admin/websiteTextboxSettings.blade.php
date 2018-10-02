@extends('main')

@section('styles')
    {{ HTML::style('css/plugins/jasny/jasny-bootstrap.min.css') }}
@endsection

@section('scripts-footer')
    {{ HTML::script('js/plugins/jasny/jasny-bootstrap.min.js') }}
    {{ HTML::script('js/website/websiteSettings.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12 website-options-heading">
                <h2 class="text-center m-b">{{ trans('salon.slider_promo_desc') }}</h2>
        </div>
    </div>

    <div id="websiteOptions" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="ibox-content">
                <h3 class="text-muted">{{ trans('salon.slider_promo_desc') }}</h3>
                @if(!$salon->website_images->isNotEmpty())
                    <h5>{{ trans('salon.no_slider_images') }}</h5>
                @endif
                <hr>
                {{ Form::open(array('id' => 'sliderPromoForm')) }}
                <div class="row">
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
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <button type="submit" class="btn btn-success">{{ trans('salon.submit') }}</button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    <script>
        var updated_successfully = '{{ trans('salon.updated_successfuly') }}';
        var prompt = '{{ trans('salon.are_you_sure') }}';
        var slider_promo_route = '{{ route('updateSliderPromo') }}';
        var slider_promo_text = '{{ trans('salon.slider_promo') }}';

        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });

    </script>
@endsection

