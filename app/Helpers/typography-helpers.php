<?php

if (!function_exists('typography_classes')) {
    function typography_classes($classes = [])
    {
        $font_family = get_option('typography.font_family');
        $font_smoothing = get_option('typography.font_smoothing');
        $font_size = get_option('typography.font_size');
        $classes = array_merge($classes, [
            "font-{$font_family}",
            $font_smoothing,
            "text-{$font_size}",
        ]);
        return css_classes($classes);
    }
}
