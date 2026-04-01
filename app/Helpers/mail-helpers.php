<?php

use Illuminate\Support\Str;

if (!function_exists('mailer_options')) {
    function mailer_options()
    {
        return collect(config('mail.mailers'))
            ->map(fn($value, $key) => [
                'label' => Str::title($key),
                'value' => $key,
            ])->toArray();
    }
}

if (!function_exists('mailer_values')) {
    function mailer_values()
    {
        return collect(mailer_options())
            ->map(fn($option) => data_get($option, 'value'))
            ->toArray();
    }
}
