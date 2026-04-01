<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use App\Livewire\Components\SettingsPage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

new #[Title('Database settings')] class extends SettingsPage
{
    public $default;
    public $migrations = [];
    public $connections = [];
    public $redis = [];

    public function prefix()
    {
        return 'database';
    }
    protected function rules()
    {
        return [
            'default' => [
                'required',
                'string',
                Rule::in(
                    array_keys($this->connections)
                ),
            ],
            'connections' => [
                'required',
                'array'
            ],
            'migrations' => [
                'required',
                'array'
            ],
            ...config_rules($this->migrations, 'migrations'),
            ...config_rules($this->redis, 'redis'),
            ...config_rules($this->connections, 'connections'),
        ];
        /* collect(arr_flat($this->connections))->each(function ($connection, $index) use (&$rules) {
            collect($connection)->each(function ($value, $key) use ($index, &$rules) {
                $ruleKey = "connections.{$index}";
                $rules[$ruleKey] = value_rule($value);
            });
        });
        return $rules; */
    }

    #[Computed()]
    public function connectionOptions()
    {
        return collect($this->connections)
            ->map(fn($connection, $key) => [
                'label' => str($key)->title()->value(),
                'value' => $key,
            ])->values()
            ->toArray();
    }
    #[Computed()]
    public function getRules()
    {
        return $this->rules();
    }
};
