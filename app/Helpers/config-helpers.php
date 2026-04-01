<?php

use Illuminate\Validation\Rule;
use App\ConfigItem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

if (!function_exists("config_sidebar")) {
    /**
     * Render config sidebar.
     */
    function config_sidebar()
    {

        return ConfigItem::sidebar();
    }
}
if (!function_exists("raw_config")) {
    /**
     * get raw config.
     * @param string $path
     */
    function raw_config($path)
    {
        return ConfigItem::raw($path);
    }
}
if (!function_exists("config_seeder")) {
    /**
     * get config seeder.
     * @param string $path
     */
    function config_seeder($path)
    {
        return collect(raw_config($path))->map(fn($value, $key) => [
            'key' => "{$path}.{$key}",
            'type' => gettype($value),
            'value' => $value,
        ])->values()->toArray();
    }
}
if (!function_exists("value_rule")) {
    /**
     * get value rule.
     * @param mixed $value
     * @return array
     */
    function value_rule($key, $value)
    {
        $type = get_field_type($key, $value);
        if ($type === 'text') {
            if (empty($value)) {
                return [
                    'nullable',
                    'string',
                    'max:255',
                ];
            } else {
                return [
                    'required',
                    'string',
                    'max:255',
                ];
            }
        }
        if ($type === 'switch') {
            return [
                'boolean',
            ];
        }
        if ($type === 'number') {
            return [
                'required',
                'integer'
            ];
        }
        if ($type === 'select') {
            return [
                'required',
                'string',
                Rule::in(select_values($key)),
            ];
        }
        if ($type === 'switch_group') {
            return [
                'required',
                'string',
                Rule::in(array_values($value)),
            ];
        }
        if (gettype($value) === 'NULL') {
            return [
                'nullable',
            ];
        }
        return [];
    }
    function value_rulee($value)
    {
        $type = gettype($value);
        $rule = [];
        if ($type === 'NULL') {
            $rule = ['nullable'];
        } elseif ($type === 'string') {
            if (empty($value)) {
                $rule = [
                    'nullable',
                    'string',
                    'max:255'
                ];
            } else {
                $rule = [
                    'required',
                    'string',
                    'max:255'
                ];
            }
        } elseif ($type === 'integer') {
            $rule = [
                'required',
                'integer',
            ];
        } elseif ($type === 'boolean') {
            $rule = [
                'boolean',
            ];
        } elseif ($type === 'array') {
            $rule = [
                'array',
            ];
        }
        return $rule;
    }
}
if (!function_exists("config_rules")) {
    /**
     * get array rules.
     * @param array $array
     * @param string|null $prefix
     * @return array
     */
    function config_rules($array, $prefix = null, $ignoreNumeric = true)
    {
        $rules = [];
        collect(arr_flat($array, $prefix, $ignoreNumeric))->each(function ($value, $key) use (&$rules) {
            $type = get_field_type($key, $value);
            if ($type === 'switch_group') {
                $rules[$key] = [
                    'nullable',
                    'array',
                ];
                $rules["{$key}.*"] = value_rule($key, $value);
            } else {
                $rules[$key] = value_rule($key, $value);
            }
        });
        return $rules;
    }
    function config_ruless(
        $array,
        $prefix = null,
        $ignoreNumeric = true
    ) {
        return collect(arr_flat($array, $prefix, $ignoreNumeric))->map(function ($value, $key) {
            return value_rule($key, $value);
        })->toArray();
    }
}
if (!function_exists("all_options")) {
    /**
     * get all config options.
     * @return array
     */
    function all_options()
    {
        $filePath = base_path("options/options.php");
        if (!File::exists($filePath)) {
            return [];
        }
        return require $filePath;
    }
}
if (!function_exists("select_options")) {
    /**
     * get select options
     * @param string $key
     * @return array
     */
    function select_options($key)
    {
        return data_get(all_options(), $key);
    }
}
if (!function_exists("select_values")) {
    /**
     * get select values
     * @param string $key
     * @return array
     */
    function select_values($key)
    {
        $options = select_options($key) ?? [];
        return collect($options)
            ->map(fn($opt) => data_get($opt, 'value'))
            ->toArray();
    }
}

if (!function_exists("config_value_safe")) {
    /**
     * get config save value.
     * @param mixex $value
     * @return string
     */
    function config_value_safe($value)
    {
        if (is_array($value)) {
            return json_encode($value);
        }
        return $value;
    }
}


if (!function_exists('get_field_type')) {
    /**
     * get field type
     * @param string $key
     * @param mixed $value
     */
    function get_field_type($key, $value)
    {
        $type = gettype($value);
        if ($type === 'string') {
            return !empty(select_options($key))
                ? 'select'
                : 'text';
        }
        if ($type === 'integer') {
            return 'number';
        }
        if ($type === 'boolean') {
            return 'switch';
        }
        if ($type === 'NULL') {
            return 'text';
        }
        if (is_numeric_array($value)) {
            return 'switch_group';
        }
    }
}
if (!function_exists('config_real')) {
    function config_real($key)
    {
        $parts = explode('.', $key);
        if (!empty($parts)) {
            if (isset($parts[0])) {
                $path = $parts[0];
                unset($parts[0]);
                $ck = implode('.', $parts);
                $raw = raw_config($path);
                return data_get($raw, $ck);
            }
        }
        return null;
    }
}
if (!function_exists('config_dirty')) {
    function config_dirty($key, $value)
    {
        $real = config_real($key);
        return $real !== $value;
    }
}
