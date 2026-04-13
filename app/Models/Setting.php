<?php

namespace App\Models;

use App\ConfigItem;
use Database\Seeders\SettingSeeder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\Finder\SplFileInfo;

class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'type',
        'key',
        'value',
    ];

    protected $appends = [
        'srcset',
    ];

    public function registerMediaCollections(): void
    {
        $settings = SettingSeeder::all()->where('type', 'file');
        if ($settings->isNotEmpty()) {
            foreach ($settings as $setting) {
                $key = data_get($setting, 'key');
                $collection = $this->addMediaCollection($key);
                $value = data_get($setting, 'value');
                if (! empty($value)) {
                    $collection->useFallbackPath($value);
                }
                $multiple = (bool) data_get($setting, 'multiple', false);
                if (! $multiple) {
                    $collection->singleFile();
                }
                $conversions = data_get($setting, 'conversions');
                if (is_array($conversions)) {
                    foreach ($conversions as $key => $value) {
                        // create conversion
                        $conversion = $this->addMediaConversion($key);

                        // width
                        $width = data_get($value, 'width');
                        if ($width) {
                            $conversion->width($width);
                        }

                        // height
                        $height = data_get($value, 'height');
                        if ($height) {
                            $conversion->height($height);
                        }

                        // quality
                        $quality = data_get($value, 'quality');
                        if ($quality) {
                            $conversion->quality($quality);
                        }

                        // format
                        $format = data_get($value, 'format');
                        if ($format) {
                            $conversion->format($format);
                        }

                        // responsive
                        $responsive = boolval(data_get($value, 'responsive'));
                        if ($responsive) {
                            $conversion->withResponsiveImages(true);
                        }

                        // queued
                        $queued = boolval(data_get($value, 'queued'));
                        if ($queued) {
                            $conversion->queued();
                        } else {
                            $conversion->nonQueued();
                        }
                    }
                }
            }
        }
    }

    public function srcset(): Attribute
    {
        return Attribute::get(function ($value, $attributes) {
            // Get the conversion name from the attributes, default to null
            /* $conversion = $attributes['conversion'] ?? null;

            // Get the first media item for this setting's key
            $media = $this->getFirstMedia($this->key);

            // If no media found, return null
            if (! $media) {
                return null;
            }

            // If conversion is specified, use it to get srcset
            if ($conversion) {
                return $media->getSrcset($conversion);
            }

            // If no conversion specified, try to find a responsive conversion
            $settings = SettingSeeder::all()->where('type', 'file')->where('key', $this->key)->first();
            if ($settings && isset($settings['conversions'])) {
                foreach ($settings['conversions'] as $conversionName => $conversionConfig) {
                    // Check if this conversion has responsive images enabled
                    if (data_get($conversionConfig, 'responsive', false)) {
                        return $media->getSrcset($conversionName);
                    }
                }
            }

            // Fallback: return srcset for the first available conversion
            $conversions = $media->getMediaConversions();
            if (! empty($conversions)) {
                $firstConversion = array_key_first($conversions);

                return $media->getSrcset($firstConversion);
            } */

            return null;
        });
    }

    public function registerMediaProperties(Media $media): void
    {
        if ($media && $media->type === 'image') {
            [$width, $height] = getimagesize($media->getPath());

            $media->setCustomProperty('width', $width);
            $media->setCustomProperty('height', $height);
        }
    }

    /**
     * get setting with key
     *
     * @return App/Models/Setting|null
     */
    public static function withKey(string $key)
    {
        return self::firstWhere('key', $key);
    }

    public function scopeWithPath($query, $path)
    {
        return $query->where(function ($q) use ($path) {
            $q->where('key', 'like', "%{$path}.%");
        });
    }

    public function getValueAttribute()
    {
        return resolve_option_value($this->type, data_get($this->attributes, 'value'));
    }

    public static function getValue($key, $defaultValue = null)
    {
        $setting = self::withKey($key);

        return $setting instanceof Setting ? ($setting->value ?? $defaultValue) : $defaultValue;
    }

    /**
     * set the value
     *
     * @param  mixed  $value
     * @return App/Models/Setting
     */
    public function setValuee($value)
    {
        if ($this->type === 'file') {
            if (empty($value)) {
                return true;
            }
            if (is_temporary_file($value)) {
                $save = $this->addMedia($value)->toMediaCollection($this->key);
                if ($save) {
                    return true;
                } else {
                    return false;
                }
            } elseif (is_temporary_files($value)) {
                $saved = true;
                foreach ($value as $file) {
                    $save = $this->addMedia($file)->toMediaCollection($this->key);
                    if (! $save) {
                        $saved = false;
                    }
                }

                return $saved;
            }
        }
        if ($this->type === 'array') {
            $value = is_array($value) ? $value : [];
            $this->value = json_encode($value);
        } elseif ($this->type === 'boolean') {
            $this->value = (bool) $value;
        } else {
            $this->value = $value;
        }

        return $this->save();
    }

    /**
     * set the value
     *
     * @param  mixed  $value
     * @return App/Models/Setting
     */
    public function setValue($value)
    {
        if ($this->type === 'array') {
            $value = is_array($value) ? $value : [];
            $this->value = json_encode($value);
        } elseif ($this->type === 'boolean') {
            $this->value = (bool) $value;
        } elseif ($this->type === 'file') {
            $this->value = null;
        } else {
            $this->value = $value;
        }

        return $this;
    }

    /**
     * update value
     *
     * @param  string|null  $type
     * @return bool
     */
    public static function updateValue(string $key, mixed $value, $type = null)
    {
        $data = [
            'key' => $key,
        ];
        if (! empty($type)) {
            $data['type'] = $type;
        }
        $setting = static::firstOrCreate($data);
        $setting->setValue($value);
        if ($type === 'file') {
            if (empty($value)) {
                return true;
            }
            if (is_temporary_file($value)) {
                $save = $setting->addMedia($value)->toMediaCollection($key);
                if ($save) {
                    return true;
                } else {
                    return false;
                }
            } elseif (is_temporary_files($value)) {
                $saved = true;
                foreach ($value as $file) {
                    $save = $setting->addMedia($file)->toMediaCollection($key);
                    if (! $save) {
                        $saved = false;
                    }
                }

                return $saved;
            }
        } else {
            return $setting->save();
        }
    }

    public function getPreviews($temporary = null)
    {

        return $temporary ? previews($temporary, $this->getMedia($this->key)) : previews($this->getMedia($this->key));
    }

    public static function getType(string $key, $default = null)
    {
        $setting = self::withKey($key);

        return data_get($setting, 'type', $default);
    }

    public function multiple(): Attribute
    {
        return Attribute::get(fn () => get_option_multiple($this->key));
    }

    /**
     * Get srcset for a specific conversion
     */
    public function getSrcset(?string $conversion = null): ?string
    {
        /* $media = $this->getFirstMedia($this->key);

        if (! $media) {
            return null;
        }

        if ($conversion) {
            return $media->getSrcset($conversion);
        }

        // Try to find a responsive conversion
        $settings = SettingSeeder::all()->where('type', 'file')->where('key', $this->key)->first();
        if ($settings && isset($settings['conversions'])) {
            foreach ($settings['conversions'] as $conversionName => $conversionConfig) {
                if (data_get($conversionConfig, 'responsive', false)) {
                    return $media->getSrcset($conversionName);
                }
            }
        }

        // Fallback to first available conversion
        $conversions = $media->getMediaConversions();
        if (! empty($conversions)) {
            $firstConversion = array_key_first($conversions);

            return $media->getSrcset($firstConversion);
        } */

        return null;
    }

    /**
     * Resolve a setting from various input types
     *
     * @param  int|string|Setting  $setting  Can be a Setting instance, ID, or key string
     * @return Setting|null Returns the Setting instance or null if not found
     */
    public static function resolve(int|string|Setting $setting)
    {
        if ($setting instanceof Setting) {
            return $setting;
        }
        if (is_int($setting) || is_numeric($setting)) {
            return static::find($setting);
        }

        if (is_string($setting)) {
            return self::firstWhere('key', $setting);
        }

        return null;
    }

    /**
     * Render config sidebar.
     */
    public static function sidebarItems()
    {
        return ConfigItem::files()->map(function (SplFileInfo $file) {
            $key = $file->getFilenameWithoutExtension();
            $title = Str::title(Str::replace(['.', '_', '-'], ' ', $key));
            $transKey = "config.{$key}";
            $trans = __($transKey);
            $label = $trans !== $transKey ? $trans : $title;

            return [
                'label' => $label,
                'href' => route('dashboard.settings.page', $key),
                'icon' => config("icons.config.{$key}"),
                'navigate' => true,
                'atts' => [
                    'wire:current' => 'active',
                ],
            ];
        });
    }

    /**
     * Render configsidebar.
     */
    public static function sidebar()
    {
        $items = [
            ...[
                [
                    'label' => __('Manage settings'),
                    'href' => route('dashboard.settings'),
                    'icon' => 'bi-gear-wide-connected',
                    'navigate' => true,
                    'atts' => [
                        'wire:current' => 'active',
                    ],
                ],
            ],
            ...self::sidebarItems(),
        ];
        // dd($items);
        $args = [
            'label' => __('Settings'),
            'icon' => 'bi-gear-wide-connected',
            'open' => true,
            'items' => $items,
        ];

        return view('components.nav-link-collapse', $args)->render();
    }

    public function label(): Attribute
    {
        return Attribute::get(function () {
            return Str::title(Str::replace(['.', '-', '_'], ' ', $this->key));
        });
    }

    public static function seedData()
    {
        return SettingSeeder::defaultSettings();
    }

    public function render($atts = [])
    {
        $setting = $this;

        return view('components.setting-field', compact('setting', 'atts'));
    }

    public static function toConfig()
    {
        $config = [];
        $settings = Setting::all();
        $settings->each(function (Setting $setting) use (&$config) {
            data_set($config, $setting->key, $setting->value);
        });

        return $config;
    }
}
