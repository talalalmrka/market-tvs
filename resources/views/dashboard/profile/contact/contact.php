<?php

use App\Models\User;
use App\Rules\ValidPhoneNumber;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public User $user;

    #[Validate()]
    public $phone;

    #[Validate()]
    public $url;

    #[Validate()]
    public $whatsapp;

    #[Validate()]
    public $facebook;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->fill($this->user->getMetas('phone', 'url', 'whatsapp', 'facebook'));
    }

    public function rules()
    {
        return [
            'phone' => ['nullable', new ValidPhoneNumber],
            'url' => ['nullable', 'url', 'max:250'],
            'whatsapp' => ['nullable', 'string', 'max:250'],
            'facebook' => ['nullable', 'url', 'max:250'],
        ];
    }

    public function save()
    {
        $this->validate();
        $save = $this->user->saveMetas($this->only(['phone', 'url', 'whatsapp', 'facebook']));
        if ($save) {
            session()->flash('status', __('Saved.'));
        } else {
            $this->addError('status', __('Save failed!'));
        }
    }
};
