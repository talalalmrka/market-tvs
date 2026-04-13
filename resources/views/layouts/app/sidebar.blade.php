@props([
    'title' => '',
    'showTitle' => true,
    'description' => null,
    'navbarClass' => null,
    'navbarAtts' => [],
    'seoTitle' => null,
    'mainClass' => null,
    'mainAtts' => [],
    'containerClass' => null,
    'containerAtts' => [],
    'class' => null,
    'atts' => [],
])
@php
    $apiUrl = route('dashboard.search');
@endphp
<x-layouts::app.layout x-data="DashboardSearch('{{ $apiUrl }}')" :title="isset($title) ? $title : null">
    <x-slot name="script">
        @vite(['resources/js/dashboard.js'])
        @livewireScriptConfig
    </x-slot>
    <x-slot name="style">
        @vite(['resources/css/app.css'])
        @livewireStyles
    </x-slot>
    <div {!! $attributes->merge([
        ...[
            'class' => css_classes(['min-h-screen bg-primary/3 dark:bg-gray-900', $class]),
        ],
        ...$atts,
    ]) !!}>
        <div class="offcanvas offcanvas-start offcanvas-primary expand-lg dashboard-sidebar" id="dashboard-sidebar">
            <div class="offcanvas-header flex items-center gap-2 justify-between h-14 border-b border-b-black/15">
                <x-logo theme="light" :label-enabled="true" data-theme="dark" class="font-semibold"
                    label-class="text-white" />
                <button class="offcanvas-close text-white lg:hidden">
                    <i class="icon bi-x-lg"></i>
                </button>
            </div>
            <div class="offcanvas-body pb-6">
                <nav class="sidebar-nav">
                    {!! dashboard_sidebar() !!}
                </nav>
            </div>
        </div>
        <main @atts(['class' => css_classes(['lg:ps-64 min-h-75vh relative', $mainClass])], $mainAtts)>
            <div class="navbar h-14 bg-white dark:bg-gray-700 shadow-xs sticky top-0">
                <div class="nav">
                    <button class="navbar-brand nav-link lg:hidden offcanvas-toggle" data-fg-toggle="offcanvas"
                        data-fg-target="#dashboard-sidebar">
                        <i class="icon bi-list text-2xl"></i>
                    </button>
                    <x-dashboard-top-search />
                    @if (isset($navbar))
                        {!! $navbar !!}
                    @endif
                </div>

                <div class="nav">
                    <button type="button" class="nav-link dark-mode-toggle">
                    </button>
                    <div class="dropdown">
                        <button type="button" class="nav-link dropdown-toggle">
                            @guest
                                <i class="icon bi-person-fill"></i>
                            @endguest
                            @auth
                                <span>{{ auth()->user()->name }}</span>

                            @endauth
                            <i class="icon bi-chevron-down w-3 h-3"></i>
                        </button>
                        <div class="dropdown-menu dropdown-end w-auto max-w-auto">
                            @guest
                                <a href="{{ route('login') }}" class="dropdown-link">
                                    <i class="icon bi-box-arrow-in-right"></i>
                                    <span>{{ __('Sign in') }}</span>
                                </a>
                                <a href="{{ route('register') }}" class="dropdown-link">
                                    <i class="icon bi-person-plus"></i>
                                    <span>{{ __('Register') }}</span>
                                </a>
                            @endguest
                            @auth
                                <a href="{{ route('profile') }}"
                                    class="dropdown-link border-b pt-0 border-gray-200 dark:border-gray-700">
                                    <div class="text-sm flex items-center gap-2">
                                        <img src="{{ auth()->user()->avatar }}" class="w-8 h-8 rounded-full"
                                            alt="{{ auth()->user()->name }}">
                                        <div class="flex-1">
                                            <div>
                                                {{ auth()->user()->name }}
                                            </div>
                                            <div class="text-muted text-xs overflow-hidden text-ellipsis">
                                                {{ auth()->user()->email }}
                                            </div>
                                        </div>

                                    </div>
                                </a>
                                <a href="{{ route('profile.edit') }}"
                                    class="dropdown-link border-b pt-0 border-gray-200 dark:border-gray-700">
                                    <div class="text-sm flex items-center gap-2">
                                        <img src="{{ auth()->user()->avatar }}" class="w-8 h-8 rounded-full"
                                            alt="{{ auth()->user()->name }}">
                                        <div class="flex-1">
                                            <div>
                                                {{ auth()->user()->name }}
                                            </div>
                                            <div class="text-muted text-xs overflow-hidden text-ellipsis">
                                                {{ auth()->user()->email }}
                                            </div>
                                        </div>

                                    </div>
                                </a>

                                <a href="{{ route('dashboard') }}" class="dropdown-link">
                                    <i class="icon bi-speedometer"></i>
                                    <span>{{ __('Dashboard') }}</span>
                                </a>
                                <a href="{{ route('profile.edit') }}" class="dropdown-link">
                                    <i class="icon bi-person-gear"></i>
                                    <span>{{ __('Profile') }}</span>
                                </a>
                                @can('manage_settings')
                                    <a href="{{ url('dashboard/settings/general') }}" class="dropdown-link">
                                        <i class="icon bi-gear-wide-connected"></i>
                                        <span>{{ __('Settings') }}</span>
                                    </a>
                                @endcan
                                <a href="{{ route('logout') }}" class="dropdown-link">
                                    <i class="icon bi-box-arrow-right"></i>
                                    <span>{{ __('Logout') }}</span>
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <div @atts(['class' => css_classes(['px-2 lg:px-4 p-4 pb-20', $containerClass])], $containerAtts)>
                @if (isset($title) || isset($actions))
                    <div class="md:flex-space-2 justify-between">
                        @if (isset($title))
                            <h3 class="text-gray-500 dark:text-white text-2xl">{{ $title }}</h3>
                        @endif
                        @if (isset($actions))
                            <div class="flex-space-2 mb-3 md:mb-0 flex-1 md:justify-end">
                                {{ $actions }}
                            </div>
                        @endif
                    </div>
                @endif
                {{ $slot }}
            </div>
        </main>
    </div>
    <x-button-back-top class="bottom-4 end-4" />
    <x-slot name="footer">
        <x-dashboard-search-dialog />
    </x-slot>
</x-layouts::app.layout>
