@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])

        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

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
