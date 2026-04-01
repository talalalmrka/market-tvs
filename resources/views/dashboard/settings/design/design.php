<?php

use App\Livewire\Components\SettingsPage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('Design settings')] class extends SettingsPage
{
    public $header_code_enabled;

    public $header_code;

    public $backtop_enabled;

    public $footer_copyrights;

    public $footer_code_enabled;

    public $footer_code;

    public $custom_css_enabled;

    public $custom_css;

    public $custom_js_enabled;

    public $custom_js;

    public $color_primary;

    public $color_secondary;

    public $color_accent;
    public function prefix()
    {
        return 'design';
    }
    public function rules()
    {
        return [
            'header_code_enabled' => ['nullable', 'boolean'],
            'header_code' => ['nullable', 'string'],
            'backtop_enabled' => ['nullable', 'boolean'],
            'footer_copyrights' => ['nullable', 'string'],
            'footer_code_enabled' => ['nullable', 'boolean'],
            'footer_code' => ['nullable', 'string'],
            'custom_css_enabled' => ['nullable', 'boolean'],
            'custom_css' => ['nullable', 'string'],
            'custom_js_enabled' => ['nullable', 'boolean'],
            'custom_js' => ['nullable', 'string'],
            'color_primary' => ['nullable', 'string', Rule::in(colors())],
            'color_secondary' => ['nullable', 'string', Rule::in(colors())],
            'color_accent' => ['nullable', 'string', Rule::in(colors())],
        ];
    }

    #[Computed()]
    public function colorOptions()
    {
        return color_options([
            [
                'label' => 'default',
                'value' => ''
            ],
        ]);
    }
};
