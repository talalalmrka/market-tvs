<x-layouts::layout :title="isset($title) ? $title : null">
    <div class="min-h-screen bg-primary/3 dark:bg-gray-900">
        <div class="offcanvas offcanvas-start offcanvas-primary expand-lg dashboard-sidebar" id="dashboard-sidebar">
            <div class="offcanvas-header flex items-center gap-2 justify-between h-14">
                <x-app-logo :sidebar="true" />
                <button class="offcanvas-close text-white lg:hidden">
                    <i class="icon bi-x-lg"></i>
                </button>
            </div>
            <div class="offcanvas-body pb-6">
                <nav class="sidebar-nav">
                    <x-nav-link wire:navigate :href="route('dashboard')" wire:current.exact="active" icon="bi-speedometer"
                        :label="__('Dashboard')" />
                    <x-nav-link wire:navigate :href="route('dashboard.old')" wire:current.exact="active" icon="bi-speedometer"
                        :label="__('Dashboard old')" />
                    <x-nav-link wire:navigate :href="route('dashboard.users')" wire:current.exact="active" icon="bi-people"
                        :label="__('Users')" />
                    @can('manage_roles')
                        <x-nav-link-collapse icon="bi-person-fill-lock" :label="__('Roles & Permissions')" :open="request()->routeIs([
                            'dashboard.roles',
                            'dashboard.roles.*',
                            'dashboard.permissions',
                            'dashboard.permissions.*',
                        ])">
                            @can('manage_roles')
                                <x-nav-link wire:navigate :href="route('dashboard.roles')" wire:current="active" icon="bi-person-gear"
                                    :label="__('Roles')" />
                            @endcan
                            @can('manage_permissions')
                                <x-nav-link wire:navigate :href="route('dashboard.permissions')" wire:current="active" icon="bi-key-fill"
                                    :label="__('Permissions')" />
                            @endcan
                        </x-nav-link-collapse>
                    @endcan
                    @can('manage_screens')
                        <x-nav-link wire:navigate :href="route('dashboard.screens')" wire:current="active" icon="bi-tv"
                            :label="__('Screens')" />
                    @endcan

                    @can('manage_media')
                        <x-nav-link wire:navigate :href="route('dashboard.media')" wire:current.exact="active" icon="bi-image"
                            :label="__('Media')" />
                    @endcan
                    <x-nav-link-collapse icon="bi-grid" :label="__('Ui Components')" :open="request()->routeIs(['dashboard.ui', 'dashboard.ui.*'])">
                        <x-nav-link wire:navigate :href="route('dashboard.ui.colors')" wire:current.exact="active" icon="bi-palette"
                            :label="__('Colors')" />
                        <x-nav-link wire:navigate :href="route('dashboard.ui.backgrounds')" wire:current.exact="active" icon="bi-palette"
                            :label="__('Backgrounds')" />
                        <x-nav-link wire:navigate :href="route('dashboard.ui.buttons')" wire:current.exact="active" icon="bi-square"
                            :label="__('Buttons')" />
                        <x-nav-link wire:navigate :href="route('dashboard.ui.modal')" wire:current.exact="active" icon="bi-window"
                            :label="__('Modal')" />
                        <x-nav-link wire:navigate :href="route('dashboard.ui.sortable')" wire:current.exact="active" icon="bi-sort-down"
                            :label="__('Sortable')" />
                        <x-nav-link wire:navigate :href="route('dashboard.ui.toast')" wire:current.exact="active" icon="bi-bell"
                            :label="__('Toast')" />
                    </x-nav-link-collapse>
                </nav>
            </div>
        </div>
        <main class="lg:ps-64 min-h-75vh relative">
            <div class="navbar h-14 bg-white dark:bg-gray-700 shadow-xs sticky top-0">
                <div class="nav">
                    <button class="navbar-brand nav-link md:hidden offcanvas-toggle" data-fg-toggle="offcanvas"
                        data-fg-target="#dashboard-sidebar">
                        <i class="icon bi-list text-2xl"></i>
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

            <div class="container px-2 lg:px-4 p-4 pb-20 {{ $containerClass ?? '' }}">
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
</x-layouts::layout>
