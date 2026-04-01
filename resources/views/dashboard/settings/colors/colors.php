<?php

use App\Livewire\Components\SettingsPage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('Colors settings')] class extends SettingsPage
{
    public $color_primary;

    public $color_secondary;

    public function title()
    {
        return __('Colors settings');
    }

    public function rules()
    {
        return [
            'color_primary' => ['nullable', 'string', Rule::in(colors())],
            'color_secondary' => ['nullable', 'string', Rule::in(colors())],
        ];
    }

    #[Computed()]
    public function colorOptions()
    {
        return color_options([
            [
                'label' => 'default',
                'value' => '',
            ],
        ]);
    }
};
