<!DOCTYPE html>
<html {!! locale_attributes() !!}>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>{{ get_the_title(isset($title) ? $title : null) }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    @vite(['resources/css/app.css'])
    @stack('styles')
</head>

<body>

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
