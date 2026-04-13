<div class="grid grid-cols-1 gap-3">
    <div class="col">
        <fgx:alert class="alert-info alert-soft">
            {{ __('Please verify your email address by clicking on the link we just emailed to you.') }}
        </fgx:alert>
    </div>
    <div class="col">
        <button wire:click="sendVerification" type="button" class="btn btn-primary gradient w-full pill">
            <span>{{ __('Resend verification email') }}</span>
            <fgx:loader wire:loading wire:target="sendVerification" />

        </button>
        <fgx:status soft class="mt-3" />
        {{-- @if (session('status') == 'verification-link-sent')
            <fgx:alert soft type="success" class="mt-3">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </fgx:alert>
        @endif --}}
    </div>
    <div class="col">
        <button wire:click="logout" type="button" class="btn btn-outline-primary w-full pill">
            {{ __('Logout') }}
        </button>
    </div>
</div>
