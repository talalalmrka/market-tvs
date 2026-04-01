<?php

use App\Livewire\Components\SettingsPage;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Auth settings')] class extends SettingsPage
{
    public $defaults = [];
    public $guards = [];
    public $providers = [];
    public $passwords = [];
    public $password_timeout;
    public function prefix()
    {
        return 'auth';
    }
    public function mount()
    {
        parent::mount();
        $this->fillGuards();
        $this->fillProviders();
        $this->fillPasswords();
    }
    protected function rules()
    {
        return [
            'defaults' => ['required', 'array'],
            'defaults.guard' => ['required', 'string', Rule::in(Arr::map($this->guards, fn($guard) => data_get($guard, 'name')))],
            'defaults.passwords' => ['required', 'string', Rule::in(Arr::map($this->passwords, fn($password) => data_get($password, 'name')))],
            'password_timeout' => ['required', 'integer', 'max:1000000'],
            'guards' => ['required', 'array'],
            'guards.*.name' => ['required', 'string', 'max:255'],
            'guards.*.driver' => ['required', 'string', 'max:255'],
            'guards.*.provider' => ['required', 'string', Rule::in(Arr::map($this->providers, fn($provider) => data_get($provider, 'name')))],
            'providers' => ['required', 'array'],
            'providers.*.name' => ['required', 'string', 'max:255'],
            'providers.*.driver' => ['required', 'string', 'max:255'],
            'providers.*.model' => ['required', 'string', 'max:255'],
            'passwords' => ['required', 'array'],
            'passwords.*.name' => ['required', 'string', 'max:255'],
            'passwords.*.driver' => ['required', 'string', 'max:255'],
            'passwords.*.table' => ['required', 'string', 'max:255'],
            'passwords.*.expire' => ['required', 'integer', 'max:255'],
            'passwords.*.throttle' => ['required', 'integer', 'max:255'],
        ];
    }
    public function fillGuards()
    {
        $this->guards = collect($this->guards)->map(function ($val, $key) {
            return [
                'name' => $key,
                'driver' => data_get($val, 'driver'),
                'provider' => data_get($val, 'provider'),
            ];
        })->values()->toArray();
    }

    public function fillProviders()
    {
        $this->providers = collect($this->providers)->map(function ($val, $key) {
            return [
                'name' => $key,
                'driver' => data_get($val, 'driver'),
                'model' => data_get($val, 'model'),
            ];
        })->values()->toArray();
    }
    public function fillPasswords()
    {
        $this->passwords = collect($this->passwords)->map(function ($val, $key) {
            return [
                'name' => $key,
                'provider' => data_get($val, 'provider'),
                'table' => data_get($val, 'table'),
                'expire' => data_get($val, 'expire'),
                'throttle' => data_get($val, 'throttle'),
            ];
        })->values()->toArray();
    }

    #[Computed()]
    public function guardOptions()
    {
        return collect($this->guards)->map(fn($guard) => [
            'label' => data_get($guard, 'name'),
            'value' => data_get($guard, 'name'),
        ]);
    }

    #[Computed()]
    public function passwordsOptions()
    {
        return collect($this->passwords)->map(fn($password) => [
            'label' => data_get($password, 'name'),
            'value' => data_get($password, 'name'),
        ]);
    }

    public function removeGuard(int $index)
    {
        unset($this->guards[$index]);
    }
    public function addGuard()
    {
        $this->guards[] = [
            'name' => '',
            'driver' => '',
            'provider' => '',
        ];
    }

    public function removeProvider(int $index)
    {
        unset($this->providers[$index]);
    }
    public function addProvider()
    {
        $this->providers[] = [
            'name' => '',
            'driver' => '',
            'model' => '',
        ];
    }
    public function removePassword(int $index)
    {
        unset($this->passwords[$index]);
    }
    public function addPassword()
    {
        $this->passwords[] = [
            'name' => '',
            'provider' => '',
            'table' => '',
            'expire' => 60,
            'throttle' => 60,
        ];
    }
};
