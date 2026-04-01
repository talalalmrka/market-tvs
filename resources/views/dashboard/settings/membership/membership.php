<?php

use App\Livewire\Components\SettingsPage;
use App\Rules\ValidRoles;
use Livewire\Attributes\Title;

new #[Title('Membership settings')] class extends SettingsPage
{
    public $users_can_register;

    public $default_roles;

    public $email_verification_required;
    public function prefix()
    {
        return 'membership';
    }
    public function rules()
    {
        return [
            'users_can_register' => ['nullable', 'boolean'],
            'default_roles' => ['required', new ValidRoles],
            'email_verification_required' => ['nullable', 'boolean'],
        ];
    }
};
