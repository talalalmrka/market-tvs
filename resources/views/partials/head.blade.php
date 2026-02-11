<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>{{ get_the_title(isset($title) ? $title : null) }}</title>
@php
    $description = isset($description) ?? get_option('description');
@endphp
@if (!empty($description))
    <meta name="description" content="{{ $description ?? get_option('description') }}">
@endif
@if (get_option('disable_search_engines', false))
    <meta name="robots" content="noindex, nofollow">
@endif
<link rel="canonical" href="{{ request()->url() }}">
<x-favicon />
@vite(['resources/css/app.css'])
@stack('styles')
@fluxAppearance
