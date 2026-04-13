<?php

use App\Livewire\Components\SettingsPage;
use App\Rules\ValidDateFormat;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('General Settings')] class extends SettingsPage
{
    public $name;

    public $description;

    public $url;

    public $admin_email;

    public $logo;

    public $logo_light;

    public $logo_width;

    public $logo_height;

    public $logo_label_enabled;

    public $favicon;

    public $locale;

    public $fallback_locale;

    public $faker_locale;

    public $timezone;

    public $date_format;

    public $datatable_date_format;

    public $cipher;

    public $key;

    public $env;

    public $debug;

    public $eruda_enabled;

    public array $maintenance = [
        'driver' => 'file',
        'store' => 'database',
    ];

    public function title()
    {
        return __('General settings');
    }

    public function prefix()
    {
        return 'app';
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'url' => ['required', 'url', 'max:255'],
            'admin_email' => ['required', 'email', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'logo_light' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'logo_width' => ['nullable', 'numeric'],
            'logo_height' => ['nullable', 'numeric'],
            'logo_label_enabled' => ['nullable', 'boolean'],
            'favicon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'locale' => ['required', 'string', Rule::in(locales())],
            'fallback_locale' => ['required', 'string', Rule::in(locales())],
            'faker_locale' => ['required', 'string', Rule::in(locales())],
            'timezone' => ['required', 'string', Rule::in(timezones())],
            'date_format' => ['required', new ValidDateFormat],
            'datatable_date_format' => ['required', new ValidDateFormat],
            'cipher' => ['required', Rule::in(app_cipher_values())],
            'key' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (Str::startsWith($value, 'base64:')) {
                        $value = base64_decode(substr($value, 7));
                    }
                    $length = strlen($value);
                    if ($this->cipher === 'AES-256-CBC' && $length !== 32) {
                        $fail('The key must be 32 bytes for AES-256-CBC.');
                    }
                    if ($this->cipher === 'AES-128-CBC' && $length !== 16) {
                        $fail('The key must be 16 bytes for AES-128-CBC.');
                    }
                },
            ],
            'env' => ['required', Rule::in(app_env_values())],
            'debug' => ['boolean'],
            'maintenance' => ['required', 'array'],
            'maintenance.driver' => ['required', Rule::in(app_maintenance_driver_values())],
            'maintenance.store' => ['required', Rule::in(app_maintenance_store_values())],
            'eruda_enabled' => ['boolean'],
        ];
    }

    #[Computed()]
    public function currentDateFormatted($format = null)
    {
        $format ??= $this->date_format;

        return Carbon::now($this->timezone ?? 'UTC')->format($format);
    }
};
