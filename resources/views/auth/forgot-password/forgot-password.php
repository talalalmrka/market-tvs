<?php

use App\Livewire\Components\AuthPage;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;

new #[Title('Forgot password')] class extends AuthPage
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        $this->addSuccess('status', __('A reset link will be sent if the account exists.'));
    }
};
