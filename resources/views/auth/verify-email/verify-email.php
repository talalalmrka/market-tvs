<?php

use App\Livewire\Actions\Logout;
use App\Livewire\Components\AuthPage;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;

new #[Title('Verify Email')] class extends AuthPage
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }
        try {
            Auth::user()->sendEmailVerificationNotification();
            $this->addSuccess('status', __('A new verification link has been sent to the email address you provided during registration.'));
            // Session::flash('status', 'verification-link-sent');
        } catch (\Exception $e) {
            $message = get_option('app.debug')
                ? __('Send failed: :error!', ['error' => $e->getMessage()])
                : __('Send failed!');
            $this->addError('status', $message);
        }
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
};
