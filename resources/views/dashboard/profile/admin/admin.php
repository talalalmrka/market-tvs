<?php

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\Permission\Models\Role;

new class extends Component
{
    #[Locked]
    public User $user;

    #[Validate()]
    public $roles;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->roles = $this->user->getRoleNames()->toArray();
    }

    public function role_options()
    {
        $roles = Role::where('guard_name', config('auth.defaults.guard', 'web'))->get();

        return $roles->map(function (Role $role) {
            return [
                'label' => $role->name,
                'value' => $role->name,
            ];
        })->toArray();
    }

    public function rules()
    {
        return [
            'roles' => ['required', 'array'],
            'roles.*' => ['required', 'string', Rule::exists('roles', 'name')->where('guard_name', config('auth.defaults.guard', 'web'))],
        ];
    }

    #[Computed()]
    public function roleOptions()
    {
        $roles = Role::where('guard_name', config('auth.defaults.guard', 'web'))->get();

        return $roles->map(function (Role $role) {
            return [
                'label' => $role->name,
                'value' => $role->name,
            ];
        })->toArray();
    }

    public function save()
    {
        $this->authorize('manage_users');
        $this->validate();
        $save = $this->user->syncRoles($this->roles);
        if ($save) {
            session()->flash('status', __('Saved.'));
        } else {
            $this->addError('status', __('Save failed!'));
        }
    }
};
