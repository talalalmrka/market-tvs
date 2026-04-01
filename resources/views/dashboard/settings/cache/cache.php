<?php

use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use App\Livewire\Components\SettingsPage;
use Illuminate\Validation\Rule;

new #[Title('Cache settings')] class extends SettingsPage
{
    public $default;
    public $prefix;
    public $stores = [];

    public function prefix()
    {
        return 'cache';
    }
    protected function rules()
    {
        return [
            'default' => [
                'required',
                'string',
                Rule::in(
                    array_keys($this->stores)
                ),
            ],
            'stores' => [
                'required',
                'array'
            ],
            ...config_rules($this->stores, 'stores'),
        ];
    }


    #[Computed()]
    public function storeOptions()
    {
        return collect(array_keys($this->stores))
            ->map(fn($store) => [
                'label' => str($store)->title()->value(),
                'value' => $store,
            ])->toArray();
    }
};
