<?php

use App\Models\Screen;

if (!function_exists('instance_screen')) {
    function instance_screen($object)
    {
        return $object instanceof Screen;
    }
}
if (!function_exists('screen_options')) {
    function screen_options()
    {
        $screens = Screen::all();
        return $screens->map(function (Screen $screen) {
            return [
                'value' => $screen->id,
                'label' => $screen->name,
            ];
        })->toArray();
    }
}
