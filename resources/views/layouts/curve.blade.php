@props([
    'title' => '',
    'showTitle' => true,
    'subtitle' => null,
    'showSubtitle' => true,
    'secondSubtitle' => null,
    'showSecondSubtitle' => true,
    'description' => null,
    'headerClass' => null,
    'headerAtts' => [],
    'seoTitle' => null,
    'color' => 'primary',
    'logoTheme' => 'light',
    'navbarClass' => null,
    'mainClass' => null,
])
@php
    $seoTitle = !empty($seoTitle) ? $seoTitle : $title;
    $hasTitle = !empty($title) && $showTitle;
    $hasSubtitle = !empty($subtitle) && $showSubtitle;
    $hasSecondSubtitle = !empty($secondSubtitle) && $showSecondSubtitle;
    $hasAnyTitle = $hasTitle || $hasSubtitle || $hasSecondSubtitle;

    $navbarColors = [
        'primary' => 'navbar-transparent-primary',
        'secondary' => 'navbar-transparent-secondary',
        'red' => 'navbar-transparent-red',
        'blue' => 'navbar-transparent-blue',
        'green' => 'navbar-transparent-green',
        'yellow' => 'navbar-transparent-yellow',
        'pink' => 'navbar-transparent-pink',
        'purple' => 'navbar-transparent-purple',
        'indigo' => 'navbar-transparent-indigo',
        'gray' => 'navbar-transparent-gray',
        'orange' => 'navbar-transparent-orange',
        'teal' => 'navbar-transparent-teal',
        'cyan' => 'navbar-transparent-cyan',
        'lime' => 'navbar-transparent-lime',
        'amber' => 'navbar-transparent-amber',
        'emerald' => 'navbar-transparent-emerald',
        'fuchsia' => 'navbar-transparent-fuchsia',
        'rose' => 'navbar-transparent-rose',
        'sky' => 'navbar-transparent-sky',
        'slate' => 'navbar-transparent-slate',
        'zinc' => 'navbar-transparent-zinc',
        'neutral' => 'navbar-transparent-neutral',
        'stone' => 'navbar-transparent-stone',
    ];
    $navbarColor = data_get($navbarColors, $color, 'navbar-transparent-primary');
@endphp
<x-layouts::layout :title="$seoTitle" :description="$description">
    <x-mobile-menu />
    @include('partials.header', [
        'class' => css_classes([
            'navbar-transparent-top fixed top-0 start-0 end-0 z-40',
            $navbarColor,
            $navbarClass,
        ]),
        'logoTheme' => $logoTheme,
    ])
    <main @cssClasses(['main flex flex-col min-h-screen', $mainClass])>
        <section class="relative bg-linear-to-br from-primary-800 to-primary-600 text-white">
            <div class="max-w-4xl mx-auto px-4 pt-18 pb-12 md:py-28 text-center relative" data-theme="dark">
                @if ($hasTitle)
                    <h1 class="text-2xl md:text-4xl">{{ $title }}</h1>
                @endif
                @if ($hasSubtitle)
                    <div class="text-lg">{!! $subTitle !!}</div>
                @endif
                @if ($hasSecondSubtitle)
                    <div class="text-sm">{!! $secondSubtitle !!}</div>
                @endif
                @if (isset($curve))
                    {!! $curve !!}
                @endif
            </div>
            <div
                class="flex flex-col items-center justify-center w-full absolute pointer-events-none bg-no-repeat -bottom-px -left-px -right-px leading-[0] overflow-hidden mx-px text-body-bg dark:text-body-bg-dark">
                <svg class="w-[120vw] md:w-[180vw]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100"
                    preserveAspectRatio="none">
                    <path class="bg-white fill-current"
                        d="M500,97C126.7,96.3,0.8,19.8,0,0v100l1000,0V1C1000,19.4,873.3,97.8,500,97z"></path>
                </svg>
            </div>
        </section>
        <div class="md:container mobile:px-2 relative flex-1">
            {{ $slot }}
        </div>
        @include('partials.footer')
    </main>



</x-layouts::layout>
