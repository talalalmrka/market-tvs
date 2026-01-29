<?php
if (!function_exists('get_option')) {
    function get_option($key, $defaultValue = null)
    {
        try {
            return config($key, $defaultValue);
            // return Setting::getValue($key, $defaultValue);
        } catch (\Exception $e) {
            return $defaultValue;
        }
    }
}
