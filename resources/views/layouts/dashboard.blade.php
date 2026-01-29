@props([
    'title' => null,
    'actions' => null,
    'showTitle' => true,
    'containerClass' => null,
])
<x-layouts::app :title="$title ?? ''">
    <slot name="style">
        @vite(['resources/css/dashboard.css'])
    </slot>
    <div class="min-h-screen bg-primary/3 dark:bg-gray-900">
        <div class="offcanvas offcanvas-start offcanvas-primary expand-lg dashboard-sidebar" id="dashboard-sidebar">
            <div class="offcanvas-header flex-space-2 items-center h-14">
                <x-app-logo :showLabel="true" theme="light" label-class="font-semibold" />
                <button class="btn offcanvas-close lg:hidden">
                    <i class="icon bi-x"></i>
                </button>
            </div>
            <div class="offcanvas-body pb-6">
                <nav class="nav vertical space-y-3">
                    <x-nav-link wire:navigate :href="route('dashboard')" wire:current.exact="active" icon="bi-speedometer"
                        :label="__('Dashboard')" />
                    <x-nav-link wire:navigate :href="route('profile.edit')" wire:current="active" icon="bi-person-gear"
                        :label="__('Profile')" />
                    @can('manage_users')
                        <x-nav-link wire:navigate :href="route('dashboard.users')" wire:current="active" icon="bi-people-fill"
                            :label="__('Users')" />
                    @endcan
                    <!-- Roles & Permissions -->
                    {{-- @can('manage_roles')
                        <x-nav-link-collapse icon="bi-person-fill-lock" :label="__('Roles & Permissions')" :open="request()->routeIs([
                            'dashboard.roles',
                            'dashboard.roles.*',
                            'dashboard.permissions',
                            'dashboard.permissions.*',
                        ])">
                            @can('manage_roles')
                                <x-nav-link wire:navigate :href="" wire:current="active" icon="bi-person-gear"
                                    :label="__('Roles')" />
                            @endcan
                            @can('manage_permissions')
                                <x-nav-link wire:navigate :href="" wire:current="active" icon="bi-key-fill"
                                    :label="__('Permissions')" />
                            @endcan
                        </x-nav-link-collapse>
                    @endcan --}}
                    @can('manage_screens')
                        <x-nav-link wire:navigate :href="route('dashboard.screens')" wire:current="active" icon="bi-tv"
                            :label="__('Media')" />
                    @endcan
                    @can('manage_media')
                        <x-nav-link wire:navigate :href="route('dashboard.media')" wire:current="active" icon="bi-image"
                            :label="__('Media')" />
                    @endcan

                    <!-- Settings -->
                    {{-- @can('manage_settings')
                        <x-nav-link-collapse icon="bi-gear-wide-connected" :label="__('Settings')" :open="true">
                            :open="request()->routeIs(['dashboard.settings', 'dashboard.settings.*'])">
                            <x-nav-link wire:navigate :href="" wire:current.exact="active"
                                icon="bi-gear-wide-connected" :label="__('Manage settings')" />
                            <x-nav-link wire:navigate :href="" wire:current="active" icon="bi-globe"
                                :label="__('General settings')" />
                            <x-nav-link wire:navigate :href="" wire:current="active" icon="bi-person-gear"
                                :label="__('Membership settings')" />
                            <x-nav-link wire:navigate :href="" wire:current="active" icon="bi-book"
                                :label="__('Reading settings')" />
                            <x-nav-link wire:navigate :href="" wire:current="active" icon="bi-link"
                                :label="__('Permalink settings')" />


                        </x-nav-link-collapse>
                    @endcan --}}
                </nav>
            </div>
        </div>
        <main class="lg:ps-64 min-h-75vh relative">
            <div class="navbar h-14 bg-white dark:bg-gray-700 shadow-xs sticky top-0">
                <div class="nav">
                    <button class="navbar-brand nav-link md:hidden offcanvas-toggle" data-fg-toggle="offcanvas"
                        data-fg-target="#dashboard-sidebar">
                        <i class="icon bi-list"></i>
                    </button>
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
                        <div class="dropdown-menu dropdown-end w-40">
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

            <div class="container px-2 lg:px-4 p-4 pb-20 {{ $containerClass ?? '' }}">
                @if (isset($title) || isset($actions))
                    <div class="md:flex-space-2 justify-between">
                        @if (isset($title) && isset($showTitle) && $showTitle === true)
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
</x-layouts::app>
