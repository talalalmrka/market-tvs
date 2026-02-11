@props([
    'class' => null,
    'atts' => [],
    'logoTheme' => 'dark',
])
<div {!! $attributes->merge([
    ...[
        'class' => css_classes(['navbar h-14 header', $class => $class]),
    ],
    ...$atts,
]) !!} class="navbar h-14">
    <button type="button" x-on:click="mobileMenu = !mobileMenu" class="nav-link lg:hidden"
        aria-label="{{ __('Toggle navigation') }}">
        @icon('bi-list w-5 h-5')
    </button>
    <x-logo class="navbar-brand" :theme="$logoTheme" context="navbar" />
    @menu('header', ['class' => 'nav navbar-nav navbar-collapse expand-lg main-menu'])
    <div class="nav">
        <button type="button" class="nav-link dark-mode-toggle" aria-label="{{ __('Toggle darkmode') }}">
        </button>
        <div class="dropdown">
            <button type="button" class="nav-link dropdown-toggle" aria-label="{{ __('Account dropdown toggle') }}">
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
                        <span>{{ __('Sign up') }}</span>
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
                    <hr>
                    <a href="{{ route('logout') }}" class="dropdown-link">
                        <i class="icon bi-box-arrow-right"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
