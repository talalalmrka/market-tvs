<?php

if (!function_exists('custom_code')) {
    /**
     * custom code
     * @param string $key
     * @return void
     */
    function custom_code($key)
    {
        $enabled = boolval(get_option("design.{$key}_enabled", false));
        $code = get_option("design.{$key}");
        if ($enabled && !empty($code)) {
            echo $code;
        }
    }
}
if (!function_exists('custom_css')) {
    /**
     * custom css
     * @return void
     */
    function custom_css()
    {
        $enabled = boolval(get_option("design.custom_css_enabled", false));
        $code = get_option('design.custom_css');
        if ($enabled && !empty($code)) {
            echo '<style>' . $code . '</style>';
        }
    }
}

if (!function_exists('custom_js')) {
    /**
     * custom css
     * @return void
     */
    function custom_js()
    {
        $enabled = boolval(get_option("design.custom_js_enabled", false));
        $code = get_option('design.custom_js');
        if ($enabled && !empty($code)) {
            echo '<script>' . $code . '</script>';
        }
    }
}
