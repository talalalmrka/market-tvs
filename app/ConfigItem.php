<?php

namespace App;

use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Wireable;
use Symfony\Component\Finder\SplFileInfo;

class ConfigItem implements Wireable
{
    public $id;

    public $type;

    public $label;

    public $value;

    public $rules;

    /**
     * Create a new class instance.
     */
    public function __construct($id, $type, $label, $value, $rules)
    {
        $this->id = $id;
        $this->type = $type;
        $this->label = $label;
        $this->value = $value;
        $this->rules = $rules;
    }

    public static function fromConfig($key, $value, ?string $prefix = null)
    {
        $rules = [];
        if (is_string($value)) {
            $rules = ['required', 'string', 'max:255'];
        }
        if (is_bool($value)) {
            $rules = ['boolean'];
        }
        if (is_int($value)) {
            $rules = ['required', 'integer'];
        }
        if (is_null($value)) {
            $rules = ['nullable', 'string', 'max:255'];
        }
        $type = gettype($value);
        $titleKey = empty($prefix) ? $key : Str::replace("{$prefix}.", '', $key);
        $title = Str::title(Str::replace(['.', '_', '-'], ' ', $titleKey));
        $transKey = "config.{$key}";
        $trans = __($transKey);
        $label = $trans !== $transKey ? $trans : $title;

        return new static($key, $type, $label, $value, $rules);
    }

    /**
     * get config files.
     * return Illuminate\Support\Collection
     */
    public static function files()
    {
        $files = collect(File::files(config_path()))
            ->filter(
                fn(SplFileInfo $file) => Str::replace(
                    $file->getFilenameWithoutExtension(),
                    '',
                    $file->getFileName()
                ) === '.php'
            )
            ->sortBy(fn(SplFileInfo $file) => $file->getFilenameWithoutExtension());

        return $files;
    }

    /**
     * get config paths.
     * return Illuminate\Support\Collection
     */
    public static function paths()
    {
        return self::files()->map(fn(SplFileInfo $file) =>  $file->getFilenameWithoutExtension());
    }

    /**
     * get config paths.
     * @param string|null $placeholder
     * return array
     */
    public static function categoryOptions($placeholder = 'All')
    {
        $options = self::paths()->map(fn(string $path) =>  [
            'label' => Str::title(Str::replace(['.', '_', '-'], ' ', $path)),
            'value' => $path,
        ])->toArray();
        if (!empty($placeholder)) {
            $options = [
                [
                    'label' => $placeholder,
                    'value' => null,
                ],
            ] + $options;
        }
        return $options;
    }

    /**
     * Render config sidebar.
     */
    public static function sidebarItems()
    {
        return self::files()->map(function (SplFileInfo $file) {
            $key = $file->getFilenameWithoutExtension();
            $title = Str::title(Str::replace(['.', '_', '-'], ' ', $key));
            $transKey = "config.{$key}";
            $trans = __($transKey);
            $label = $trans !== $transKey ? $trans : $title;

            return [
                'label' => $label,
                'href' => route('dashboard.config', $key),
                'icon' => config("icons.config.{$key}"),
                'navigate' => true,
                'atts' => [
                    'wire:current' => 'active',
                ],
            ];
        });
    }

    /**
     * Render config sidebar.
     */
    public static function sidebar()
    {
        $args = [
            'label' => __('Config'),
            'icon' => 'bi-gear-wide-connected',
            'open' => request()->routeIs([
                'dashboard.config',
                'dashboard.config.*',
            ]),
            'items' => self::sidebarItems(),
        ];

        return view('components.nav-link-collapse', $args)->render();
    }

    /**
     * Get config array from file by path.
     *
     * @param  string  $key
     * @return array
     */
    public static function raw($path)
    {
        $filePath = config_path($path . '.php');
        if (!File::exists($filePath)) {
            return [];
        }

        return require $filePath;
    }

    /**
     * Flatten Laravel config array to dot notation.
     *
     * @param  string|null  $path
     * @return Illuminate\Support\Collection
     */
    public static function flat(string $path, ?string $prefix = null)
    {
        $config = self::raw($path);

        return collect(arr_flat($config, $prefix));
    }

    /**
     * get config files.
     *
     * @param  string  $path
     * @param  string|null  $prefix
     *                               return Illuminate\Support\Collection
     */
    public static function all($path, $prefix = null)
    {
        $items = collect();
        self::flat($path, $prefix)->each(function ($value, $key) use (
            &$items,
            $prefix
        ) {
            $items->add(self::fromConfig($key, $value, $prefix));
        });

        return $items;
    }

    /**
     * get config rules.
     *
     * @param  string  $path
     * @param  string|null  $prefix
     *                               return Illuminate\Support\Collection
     */
    public static function rules($path, $prefix = null)
    {
        $rules = [];
        if (! empty($prefix)) {
            $rules[$prefix] = ['required', 'array'];
        }
        self::all($path, $prefix)->each(function ($item) use (&$rules) {
            $rules[$item->id] = $item->rules;
        });

        return $rules;
    }

    public static function arrayContent(array $array, $dep = 1)
    {
        $sep = Str::repeat("\t", $dep);
        $ret = "[\n";
        collect($array)->each(function ($value, $key) use (&$ret, $dep, $sep) {
            $prefix = is_int($key) ? '' : "'{$key}' => ";
            if (is_array($value)) {
                $ret .= "{$sep}{$prefix}";
                $ret .= self::arrayContent($value, $dep + 1);
                $ret .= ',';
                $ret .= "\n";
            } else {
                if (is_string($value)) {
                    $value = "'{$value}'";
                }
                $ret .= "{$sep}{$prefix}{$value},";
                $ret .= "\n";
            }
        });
        $endSep = $dep > 1 ? Str::repeat("\t", $dep - 1) : '';
        $ret .= "{$endSep}]";

        return $ret;
    }

    public static function fileContent($array)
    {
        $ret = "<?php \n";
        $ret .= 'return ';
        $ret .= self::arrayContent($array);
        $ret .= ';';

        return $ret;
    }

    public static function fromLivewire($data)
    {
        return new static($data['id'], $data['type'], $data['label'], $data['value'], $data['rules']);
    }

    public function toLivewire()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'label' => $this->label,
            'value' => $this->value,
            'rules' => $this->rules,
        ];
    }

    public static function fileLines($path)
    {
        $fileName = Str::lower($path) . '.php';
        $filePath = config_path($fileName);
        if (! File::exists($filePath)) {
            abort(404);
        }
        /*$lines = File::lines($filePath);
    return $lines->map(function($line, $index){
        return Str::match("(.* => .*,)", $line);
    });*/
        $content = File::get($filePath);
        preg_match_all('(.* => .*,)', $content, $matches);

        return $matches;
    }

    /**
     * get config settings.
     * return Illuminate\Support\Collection<Setting>
     */
    public static function settings()
    {
        $settings = collect();
        self::files()->each(function (SplFileInfo $file) use (&$settings) {
            $path = $file->getFilenameWithoutExtension();
            self::flat($path)->each(function ($value, $key) use (&$settings, $path) {
                $type = gettype($value);
                $setting = new Setting([
                    'type' => $type,
                    'key' => "{$path}.{$key}",
                ]);
                $setting->setValue($value);
                $settings->add($setting);
            });
        });

        return $settings;
    }

    public function render($atts = [])
    {
        $item = $this;

        return view('components.config-item', compact('item', 'atts'));
    }
}
