<!DOCTYPE html>
<html {!! locale_attributes() !!}>

<head>
    @include('partials.head')
</head>

<body
    {{ $attributes->merge([
        'x-data' => '{ mobileMenu: false }',
        'class' => css_classes([typography_classes()]),
    ]) }}>
    {{ $slot }}
    @if (get_option('app.eruda_enabled'))
        <script type="text/javascript" src="{{ url('assets/eruda/eruda.js') }}"></script>
        <script>
            eruda.init();
        </script>
    @endif
    @if (isset($script))
        {{ $script }}
    @else
        @vite(['resources/js/app.js'])
    @endif
    @stack('scripts')
    @customCode('footer_code')
    @customJs()
    @isset($footer)
        {{ $footer }}
    @endisset
</body>

</html>
