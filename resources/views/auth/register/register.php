<?php

use App\Events\LoggedIn;
use App\Livewire\Components\AuthPage;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;

new #[Title('Create Account')] class extends AuthPage
{
    #[Validate()]
    public string $name = '';

    #[Validate]
    public string $email = '';

    #[Validate]
    public string $password = '';

    #[Validate]
    public string $password_confirmation = '';

    #[Validate]
    public bool $agree = false;

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'min:4', 'max:255'],
            'password_confirmation' => ['required', 'string', 'same:password'],
            'agree' => ['required', 'accepted'],
        ];
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        if (! get_option('membership.users_can_register')) {
            $this->addError('status', __('Sorry, registration is currently unavailable, please try again later'));

            return;
        }
        $validated = $this->validate();
        $guestSessionId = Session::getId();
        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user, true);
        event(new LoggedIn('web', $user, true, $guestSessionId));

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
};
