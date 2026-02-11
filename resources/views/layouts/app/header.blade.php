@props([
    'title' => '',
    'showTitle' => true,
    'description' => null,
    'seoTitle' => null,
    'logoTheme' => 'dark',
    'navbarClass' => null,
    'navbarAtts' => [],
    'mainClass' => null,
    'mainAtts' => [],
    'containerClass' => null,
    'containerAtts' => [],
])
@php
    $seoTitle = !empty($seoTitle) ? $seoTitle : $title;
    $hasTitle = !empty($title) && $showTitle;
@endphp
<x-layouts::app.layout :title="$seoTitle" :description="$description">
    <x-mobile-menu />
    @include('partials.header', [
        'class' => $navbarClass ?? 'header sticky top-0 bg-gray-50 dark:bg-gray-700 max-w-full z-50 shadow-xs',
        'logoTheme' => $logoTheme,
        'atts' => $navbarAtts,
    ])
    <main @atts(['class' => css_classes(['main flex flex-col min-h-screen', $mainClass])], $mainAtts)>
        <div @atts(['class' => css_classes(['entry md:container mobile:px-2 relative flex-1 py-6', $containerClass])], $containerAtts)>
            @if ($hasTitle)
                <h3 class="text-gray-500 dark:text-white text-2xl">{{ $title }}</h3>
            @endif
            {{ $slot }}
        </div>
        @include('partials.footer')
    </main>
</x-layouts::app.layout>
