@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url'), 'logo' => $logo, 'location_name' => $location_name])
@endcomponent
@endslot

{{-- Body --}}
@foreach($introLines as $line)
{!! $line !!}
@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
switch ($level) {
case 'success':
    $color = 'green';
    break;
case 'error':
    $color = 'red';
    break;
default:
    $color = 'blue';
}
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{!! $actionText !!}
@endcomponent
@endisset

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
    {{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
<small class="wrong-delivery">{{ trans('main.confidentiality_mail') }}</small>
&copy; {{ date('Y') }} {{ config('app.name') }}. {{ trans('main.copyright') }}
@endcomponent
@endslot
@endcomponent
