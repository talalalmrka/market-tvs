@props(['user'])
<a title="{{ $user->email }}" href="mailto:{{ $user->email }}" class="link" target="_blank">{{ $user->email }}</a>
<div class="mt-1">
    @if ($user->hasVerifiedEmail())
        <span class="badge badge-green pill inline-flex items-center gap-0.5">
            <i class="icon bi-check2-circle"></i>
            <span>{{ __('Verified') }}</span>
        </span>
    @else
        <button wire:click="sendEmailVerification({{ $user->id }})" type="button" class="link">
            <span wire:loading.remove
                wire:target="sendEmailVerification({{ $user->id }})">{{ __('Send Verification') }}</span>
            <fgx:loader wire:loading wire:target="sendEmailVerification({{ $user->id }})" />
        </button>
    @endif
</div>
