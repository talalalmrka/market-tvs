<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

if (!function_exists('get_footer_blocks')) {
    function get_footer_blocks(): array
    {
        return is_array(get_option('footer_blocks', [])) ? get_option('footer_blocks', []) : [];
    }
}
if (!function_exists('footer_blocks_cache_key')) {
    function footer_blocks_cache_key()
    {
        $updated_at = setting('footer_blocks')?->updated_at ?? now();
        return "footer-blocks-" . md5($updated_at);
    }
}
if (!function_exists('footer_blocks')) {
    function footer_blocks()
    {
        $cacheEnabled = boolval(get_option('cache_footer_blocks_enabled'));
        if ($cacheEnabled) {
            return Cache::rememberForever(footer_blocks_cache_key(), function () {
                return get_footer_blocks();
            });
        }
        return get_footer_blocks();
    }
}
if (!function_exists('footer_blocks_default')) {
    function footer_blocks_default(): array
    {
        $path = database_path('seeders/footer-blocks.json');
        if (File::exists($path)) {
            return File::json($path);
        }
        return [];
    }
}

if (!function_exists('footer_blocks_cache')) {
    function footer_blocks_cache()
    {
        Cache::rememberForever(footer_blocks_cache_key(), function () {
            return get_footer_blocks();
        });
    }
}
if (!function_exists('footer_blocks_cache_flush')) {
    function footer_blocks_cache_flush()
    {
        Cache::forget(footer_blocks_cache_key());
    }
}
