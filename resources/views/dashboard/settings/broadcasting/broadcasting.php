<?php

use App\Livewire\Components\SettingsPage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('Broadcasting settings')] class extends SettingsPage
{
    public $default;
    public $connections = [];

    public function prefix()
    {
        return 'broadcasting';
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
            ...config_rules($this->connections, 'connections'),
        ];
        /* collect(arr_flat($this->connections))->each(function ($connection, $index) use (&$rules) {
            collect($connection)->each(function ($value, $key) use ($index, &$rules) {
                $ruleKey = "connections.{$index}";
                $rules[$ruleKey] = value_rule($value);
            });
        }); */
        // return $rules;
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
};
