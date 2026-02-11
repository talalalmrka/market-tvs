<?php

use App\Models\Page;
use App\Models\Post;
use App\Models\Setting;
use Database\Seeders\SettingSeeder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Carbon\Carbon;


if (!function_exists('setting')) {
    /**
     * Resolve a setting from various input types
     *
     * @param int|string|Setting $setting Can be a Setting instance, ID, or key string
     * @return Setting|null Returns the Setting instance or null if not found
     */
    function setting(int|string|Setting $setting)
    {
        return Setting::resolve($setting);
    }
}
if (!function_exists('instance_setting')) {
    function instance_setting($object): bool
    {
        return $object instanceof Setting;
    }
}
if (!function_exists('get_option')) {
    function get_option($key, $defaultValue = null)
    {
        try {
            return Setting::getValue($key, $defaultValue);
        } catch (\Exception $e) {
            return $defaultValue;
        }
    }
}

if (!function_exists('update_option')) {
    function update_option(string $key, mixed $value, $type = null): bool
    {
        return Setting::updateValue($key, $value, $type);
    }
}
if (!function_exists('get_option_multiple')) {
    function get_option_multiple($key, $defaultValue = false): bool
    {
        $setting = SettingSeeder::withKey($key);
        return (bool) data_get($setting, 'multiple', $defaultValue);
    }
}
if (!function_exists('get_option_previews')) {
    function get_option_previews(string $key, $temporary = null)
    {
        return Setting::withKey($key)?->getPreviews($temporary);
    }
}
if (!function_exists('get_option_type')) {
    function get_option_type(string $key)
    {
        $default_type = get_default_option_type($key);
        return Setting::getType($key, $default_type);
    }
}
if (!function_exists('get_option_image')) {
    function get_option_image($key, $conversion = '', $defaultValue = null)
    {
        return Setting::withKey($key)?->getFirstMediaUrl($key, $conversion) ?? $defaultValue;
    }
}
if (!function_exists('get_option_file')) {
    function get_option_file($key, $defaultValue = null): Media | null
    {
        return Setting::withKey($key)?->getFirstMedia($key) ?? $defaultValue;
    }
}
if (!function_exists('get_default_option')) {
    function get_default_option(string $key, $defaultValue = null)
    {
        return SettingSeeder::getDefaultOption($key, $defaultValue);
    }
}
if (!function_exists('get_default_setting')) {
    function get_default_setting(string $key): array|null
    {
        return SettingSeeder::withKey($key);
    }
}
if (!function_exists('get_default_option_type')) {
    function get_default_option_type(string $key, $defaultValue = null)
    {
        return SettingSeeder::getDefaultOptionType($key, $defaultValue);
    }
}

if (!function_exists('resolve_option_value')) {
    function resolve_option_value($type, $value)
    {
        return match ($type) {
            'boolean' => (bool) $value,
            'array' => is_json($value) ? json_decode($value, true) : (is_array($value) ? $value : []),
            'number' => !empty($value) ? intval($value) : $value,
            default => $value,
        };
    }
}

if (!function_exists('setting_type_options')) {
    function setting_type_options()
    {
        $settings = collect(SettingSeeder::defaultSettings());
        $types = array_unique($settings->map(fn($setting) => data_get($setting, 'type'))->toArray());
        return collect($types)->map(fn($type) => [
            'label' => ucfirst($type),
            'value' => $type,
        ])->toArray();
        /*
        return [
            [
                'label' => __('String'),
                'value' => 'string',
            ],
            [
                'label' => __('Boolean'),
                'value' => 'boolean',
            ],
            [
                'label' => __('Number'),
                'value' => 'number',
            ],
            [
                'label' => __('Number'),
                'value' => 'number',
            ],
        ];
        */
    }
}

if (!function_exists('setting_types')) {
    function setting_types(): array
    {
        return collect(setting_type_options())->map(fn($option) => data_get($option, 'value'))->toArray();
        /*return arr_map(setting_type_options(), function ($option) {
            return data_get($option, 'value');
        });*/
    }
}
if (!function_exists('front_type_options')) {
    function front_type_options()
    {
        return [
            [
                'label' => __('Your latest posts'),
                'value' => 'posts',
            ],
            [
                'label' => __('A static page'),
                'value' => 'page',
            ],
        ];
    }
}
if (!function_exists('front_types')) {
    function front_types()
    {
        return arr_map(front_type_options(), function ($option) {
            return data_get($option, 'value');
        });
    }
}
if (!function_exists('site_name')) {
    function site_name()
    {
        return get_option('name', config('app.name'));
    }
}
if (!function_exists('site_url')) {
    function site_url()
    {
        return get_option('url', config('app.url'));
    }
}
if (!function_exists('site_description')) {
    function site_description()
    {
        return get_option('description', config('app.description'));
    }
}
if (!function_exists('favicon')) {
    function favicon()
    {
        $setting = setting('favicon');
        return $setting ? $setting->getFirstMediaUrl('favicon') : null;
    }
}

if (!function_exists('logo')) {
    function logo()
    {
        $setting = setting('logo');
        return $setting ? $setting->getFirstMediaUrl('logo') : null;
    }
}
if (!function_exists('logo_light')) {
    function logo_light()
    {
        $setting = setting('logo_light');
        return $setting ? $setting->getFirstMediaUrl('logo_light') : null;
    }
}
if (!function_exists('logo_path')) {
    function logo_path()
    {
        $setting = setting('logo');
        return $setting ? $setting->getFirstMedia('logo')?->getPath() : null;
    }
}
if (!function_exists('logo_light_path')) {
    function logo_light_path()
    {
        $setting = setting('logo_light');
        return $setting ? $setting->getFirstMedia('logo_light')?->getPath() : null;
    }
}

if (!function_exists('front_page')) {
    function front_page(): Post | null
    {
        $front_page_id = get_option('front_page');
        return $front_page_id && is_numeric($front_page_id) ? Post::where('type', 'page')->find($front_page_id) : null;
    }
}
if (!function_exists('front_page_id')) {
    function front_page_id(): int | null
    {
        return front_page()?->id;
    }
}

if (!function_exists('blog_page')) {
    function blog_page(): Post | null
    {
        $blog_page_id = get_option('posts_page');
        return $blog_page_id && is_numeric($blog_page_id) ? Post::where('type', 'page')->find($blog_page_id) : null;
    }
}
if (!function_exists('blog_page_id')) {
    function blog_page_id(): int | null
    {
        return blog_page()?->id;
    }
}
if (!function_exists('home_page')) {
    function home_page(): Post|null
    {
        return Post::type('page')->slug('home');
    }
}
if (!function_exists('posts_page')) {
    function posts_page(): Post|null
    {
        return Post::type('page')->slug('blog');
    }
}

if (!function_exists('app_date_format')) {
    function app_date_format()
    {
        return get_option('date_format', 'j F، Y');
    }
}

if (!function_exists('date_formatted')) {
    function date_formatted($date, $format = null)
    {
        try {
            if (empty($format)) {
                $format = app_date_format();
            }
            if (is_string($date)) {
                $date = Carbon::parse($date);
            }
            return date_format($date, $format);
        } catch (\Exception $e) {
            return '';
        }
    }
}
/**
 * get collection of all settings
 * @return \Illuminate\Support\Collection
 */
if (!function_exists('settings')) {
    function settings()
    {
        try {
            return Setting::all()->pluck('value', 'key');
        } catch (\Exception $e) {
            return collect();
        }
    }
}

if (!function_exists('footer_copyrights')) {
    function footer_copyrights()
    {
        $template = get_option('footer_copyrights', 'Copyrights reserved @ :link | :year');
        $name = get_option('name');
        $description = get_option('name');
        $url = get_option('url');
        $logo = view('components.logo');
        $logo_light = view('components.logo', ['theme' => 'light']);
        $link = '<a href="' . $url . '" title="' . $name . '">' . $name . '</a>';
        $year = date('Y');
        $find = [
            ':name',
            ':description',
            ':url',
            ':logo',
            ':logo_light',
            ':link',
            ':year',
        ];
        $replace = [
            $name,
            $description,
            $url,
            $logo,
            $logo_light,
            $link,
            $year,
        ];
        return str_ireplace($find, $replace, $template);
    }
}
