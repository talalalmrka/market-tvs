@props([
    'title' => '',
    'description' => '',
    'navbarClass' => null,
    'bodyClass' => null,
    'mainClass' => null,
    'seo_title' => null,
    'seo_description' => null,
    'logoTheme' => 'dark',
])
<x-layouts::layout :title="$title ?? ''" :description="$description">
    <x-mobile-menu />
    @include('partials.header', [
        'class' => $navbarClass ?? 'header sticky top-0 bg-gray-50 dark:bg-gray-700 max-w-full z-50 shadow-xs',
        'logoTheme' => $logoTheme,
    ])
    <main class="main min-h-[75vh] {{ $mainClass ?? '' }}">
        {{ $slot }}
    </main>
    @include('partials.footer')
    @if (boolval(get_option('custom_css_enabled')))
        @push('head')
            <style>
                {!! get_option('custom_css') !!}
            </style>
        @endpush
    @endif
    @if (boolval(get_option('header_code_enabled')))
        @push('head')
            {!! get_option('header_code') !!}
        @endpush
    @endif
    @if (boolval(get_option('ads_auto_enabled')))
        @push('head')
            {!! get_option('ads_auto_code') !!}
        @endpush
    @endif
    @if (boolval(get_option('custom_js_enabled')))
        @push('head')
            <script>
                {!! get_option('custom_js') !!}
            </script>
        @endpush
    @endif
    @if (boolval(get_option('footer_code_enabled')))
        @push('footer')
            {!! get_option('footer_code') !!}
        @endpush
    @endif
    @if (isset($footer))
        {{ $footer }}
    @endif
</x-layouts::layout>
