<!DOCTYPE html>
<html {!! locale_attributes() !!}>

<head>
    @include('partials.head')
</head>

<body x-data="{ mobileMenu: false }">

    {{ $slot }}
    @if (config('app.eruda_enabled'))
        <script type="text/javascript" src="{{ url('assets/eruda/eruda.js') }}"></script>
        <script>
            eruda.init();
        </script>
    @endif
    @vite(['resources/js/app.js'])
    @stack('scripts')
</body>

</html>
