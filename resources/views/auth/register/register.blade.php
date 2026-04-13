<div>
    @if (get_option('membership.users_can_register'))
        <form wire:submit="register">
            <div class="grid grid-cols-1 gap-3">
                <div class="col">
                    <fgx:input wire:model.live="name" id="name" class="pill" :label="__('Name')" autofocus
                        autocomplete="name" :placeholder="__('Name')" startIcon="bi-person-fill" />
                </div>
                <div class="col">
                    <fgx:input wire:model.live="email" id="email" class="pill" :label="__('Email address')"
                        autofocus
                        autocomplete="email" :placeholder="__('Email address')" startIcon="bi-envelope-fill" />
                </div>
                <div class="col">
                    <fgx:input type="password" wire:model.live="password" id="password" class="pill"
                        :label="__('Password')" :placeholder="__('Password')" autofocus autocomplete="new-password"
                        startIcon="bi-key-fill" />
                </div>
                <div class="col">
                    <fgx:input type="password" wire:model.live="password_confirmation" id="password_confirmation"
                        class="pill" :label="__('Confirm password')" :placeholder="__('Confirm password')" autofocus
                        autocomplete="new-password" startIcon="bi-key-fill" />
                </div>
                <div class="col">
                    <fgx:switch id="agree" wire:model.live="agree" :label="__('Agree terms and policy.')" />
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary gradient w-full pill">
                        <i class="icon bi-person-plus"></i>
                        <span wire:loading.remove wire:target="register">{{ __('Create Account') }}</span>
                        <fgx:loader wire:loading wire:target="register" />
                    </button>
                    <fgx:status class="alert-soft xs mt-2" />
                </div>
                @if (route_has('login'))
                    <div class="col">
                        <div class="flex-space-2 justify-center text-sm">
                            <span>{{ __('Already registered?') }}</span>
                            <a href="{{ route('login') }}" class="link">{{ __('Login') }}</a>
                        </div>
                    </div>
                @endif
            </div>
        </form>
    @else
        <div>
            <fgx:alert :content="__('Sorry, registration is currently unavailable, please try again later')" />
            <div class="flex-space-2 justify-center text-sm mt-4">
                <span>{{ __('Already registered?') }}</span>
                @if (route_has('login'))
                    <a href="{{ route('login') }}" class="link">{{ __('Login') }}</a>
                @endif
            </div>
        </div>

    @endif

</div>
