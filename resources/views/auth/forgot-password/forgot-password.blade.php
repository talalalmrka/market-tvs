<form wire:submit="sendPasswordResetLink">
    <div class="grid grid-cols-1 gap-3">
        <div class="col">
            <fgx:input wire:model.live="email" id="email" class="pill" :label="__('Email address')" autofocus
                autocomplete="email" :placeholder="__('Email')" startIcon="bi-envelope-fill" />
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary gradient w-full pill">
                <i class="icon bi-send"></i>
                <span wire:loading.remove
                    wire:target="sendPasswordResetLink">{{ __('Email password reset link') }}</span>
                <fgx:loader wire:loading wire:target="sendPasswordResetLink" />
            </button>
            <fgx:status class="alert-soft xs mt-2" />
        </div>
        @if (route_has('login'))
            <div class="col">
                <div class="flex-space-2 justify-center text-sm">
                    <span>{{ __('Back to') }}</span>
                    <a href="{{ route('login') }}" class="link">{{ __('Login') }}</a>
                </div>
            </div>
        @endif

    </div>
</form>
