<div>
    <div class="grid grid-cols-1 gap-4">
        <div class="col">
            <fgx:input
                id="username"
                :label="__('Email')"
                start-icon="bi-envelope"
                class="pill" />
        </div>
        <div class="col">
            <fgx:input
                id="password"
                :label="__('Password')"
                start-icon="bi-key"
                class="pill" />
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary w-full pill">
                <i class="icon bi-box-arrow-in-right"></i>
                <span wire:loading.remove wire:target="login">{{ __('Login') }}</span>
                <fgx:loader wire:loading wire:target="login" />
            </button>
            <fgx:status class="alert-soft xs mt-2" />
        </div>
    </div>
</div>
