<?php

use App\Livewire\Components\SettingsPage;
use Livewire\Attributes\Title;

new #[Title('Ads settings')] class extends SettingsPage
{
    public $auto_enabled;

    public $auto_code;

    public $above_content_enabled;

    public $above_content_code;

    public $below_content_enabled;

    public $below_content_code;
    public function prefix()
    {
        return 'ads';
    }
    public function rules()
    {
        return [
            'auto_enabled' => ['nullable', 'boolean'],
            'auto_code' => ['nullable', 'string'],
            'above_content_enabled' => ['nullable', 'boolean'],
            'above_content_code' => ['nullable', 'string'],
            'below_content_enabled' => ['nullable', 'boolean'],
            'below_content_code' => ['nullable', 'string'],
        ];
    }
};
