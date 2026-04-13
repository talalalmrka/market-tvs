<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public User $user;

    #[Validate()]
    public string $password = '';

    #[Validate()]
    public string $password_confirmation = '';

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function rules()
    {
        return [
            'password' => ['required', 'string', 'min:4', 'max:255'],
            'password_confirmation' => ['required', 'string', 'same:password'],
        ];
    }

    public function save()
    {
        $this->Validate();
        $crypted = Hash::make($this->password);
        // dd($crypted);
        $save = $this->user->update([
            'password' => Hash::make($this->password),
        ]);
        if ($save) {
            $this->reset('password', 'password_confirmation');
            $this->dispatch('password-updated');
            session()->flash('status', __('Saved'));
        } else {
            $this->addError('status', __('Save failed'));
        }
    }
};
