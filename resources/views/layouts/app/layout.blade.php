<!DOCTYPE html>
<html {!! locale_attributes() !!}>

<head>
    @include('partials.head')
</head>

<body x-data="{ mobileMenu: false }">

    {{ $slot }}
    @if (get_option('eruda_enabled'))
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
</body>

</html>
