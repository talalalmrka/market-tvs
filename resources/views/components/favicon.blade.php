@php
    $setting = setting('favicon');
@endphp
@if ($setting)
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $setting->getFirstMediaUrl('favicon', 'favicon-16') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $setting->getFirstMediaUrl('favicon', 'favicon-32') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ $setting->getFirstMediaUrl('favicon', 'favicon-48') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ $setting->getFirstMediaUrl('favicon', 'apple-touch-120') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ $setting->getFirstMediaUrl('favicon', 'apple-touch-152') }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ $setting->getFirstMediaUrl('favicon', 'apple-touch-167') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ $setting->getFirstMediaUrl('favicon', 'apple-touch-180') }}">
    <link rel="icon" type="image/png" sizes="192x192"
        href="{{ $setting->getFirstMediaUrl('favicon', 'android-192') }}">
    <link rel="icon" type="image/png" sizes="512x512"
        href="{{ $setting->getFirstMediaUrl('favicon', 'android-512') }}">
@endif
