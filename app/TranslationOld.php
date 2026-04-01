<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class TranslationOld
{
    /**
     * locale name
     *
     * @var string
     */
    public $locale;

    /**
     * words collection
     *
     * @var \Illuminate\Support\Collection
     */
    public $words;

    /**
     * Create a new class instance.
     */
    public function __construct($locale, $words = [])
    {
        $this->locale = $locale;
        $this->initWords($words);
    }

    private function initWords($override = [])
    {
        $words = $override;
        $path = lang_path("{$this->locale}.json");
        if (File::exists($path)) {
            $words = [
                ...json_decode(File::get($path), true) ?? [],
                ...$override,
            ];
        }
        $this->words = collect($words)->sortKeys();
    }

    /**
     * words count with status null, completed, pending default null
     *
     * @param  string|null  $status  null, completed, pending default null, the null is all.
     * @return int<0, max>
     */
    public function wordsCount($status = null)
    {
        $query = $this->words;
        if (! empty($status)) {
            if ($status === 'completed') {
                $query = $query->filter(fn ($t) => ! empty(trim($t)));
            }
            if ($status === 'pending') {
                $query = $query->filter(fn ($t) => empty(trim($t)));
            }
        }

        return $query->count();
    }

    /**
     * find by locale
     *
     * @return \App\Translation|null
     */
    public static function find(string $locale)
    {
        $path = lang_path("{$locale}.json");
        if (File::exists($path)) {
            return new static($locale);
        } else {
            return null;
        }
    }

    /**
     * all files
     *
     * @return \Illuminate\Support\Collection
     */
    private static function allFiles()
    {
        $files = File::files(lang_path());

        return collect($files)->filter(fn (SplFileInfo $file) => $file->getType() === 'file' && $file->getExtension() === 'json');
    }

    /**
     * all translations
     *
     * @return \Illuminate\Support\Collection
     */
    public static function all()
    {
        return self::allFiles()->map(fn (SplFileInfo $file) => self::find($file->getFilenameWithoutExtension()))->filter(fn ($l) => ! empty($l))->sortKeys();
    }

    /**
     * create new locale
     *
     * @param  string  $locale
     * @param  array  $words  default array()
     * @return \App\Translation|false
     */
    public static function create($locale, $words = [])
    {
        if (self::find($locale)) {
            throw new \Exception("The translation $locale already exist!");
        }
        $path = lang_path("{$locale}.json");
        if (! File::exists(base_path('lang'))) {
            File::makeDirectory(base_path('lang'), 0755, true);
        }
        $put = File::put($path, json_encode($words, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        if ($put) {
            return self::find($locale);
        } else {
            return $put;
        }
    }

    /**
     * delete
     *
     * @return bool
     */
    public function delete()
    {
        $path = lang_path("{$this->locale}.json");

        return File::delete($path);
    }

    /**
     * save
     *
     * @return bool
     */
    public function save($words)
    {
        $path = lang_path("{$this->locale}.json");
        if (! File::exists(base_path('lang'))) {
            File::makeDirectory(base_path('lang'), 0755, true);
        }

        return File::put($path, json_encode($words, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Load translations for locale
     *
     * @return \App\Translation
     */
    public function syncWords(array $override = [])
    {
        $path = lang_path("{$this->locale}.json");
        if (! File::exists(base_path('lang'))) {
            File::makeDirectory(base_path('lang'), 0755, true);
        }
        if (! File::exists($path)) {
            File::put($path, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
        $oldWords = [
            ...$this->words->toArray(),
            ...$override,
        ];
        $strings = $this->findTranslatableStrings();
        $words = [];
        foreach ($strings as $string) {
            $words[$string] = data_get($oldWords, $string, '');
        }
        $words = collect($words)->sortKeys();
        File::put($path, json_encode($words->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->words = $words;

        return $this;
    }

    /**
     * Find the translatable strings strings from scan project for wraped with__(), @lang()
     *
     * @return \Illuminate\Support\Collection
     */
    public function findTranslatableStrings()
    {
        $directories = [
            base_path('resources/views'),
            app_path(),
            base_path('routes'),
            base_path('app'),
        ];
        $strings = [];

        foreach ($directories as $directory) {
            $files = File::allFiles($directory);

            foreach ($files as $file) {
                $contents = $file->getContents();

                // Match __() calls with simple strings
                if (preg_match_all("/__\(\s*['\"](.*?)['\"]\s*(?:,\s*\[.*?\])?\)/", $contents, $matches)) {
                    $strings = array_merge($strings, $matches[1]);
                }

                // Match @lang() calls with simple strings
                if (preg_match_all("/@lang\(\s*['\"](.*?)['\"]\s*(?:,\s*\[.*?\])?\)/", $contents, $matches)) {
                    $strings = array_merge($strings, $matches[1]);
                }

                // Match __() calls with dynamic placeholders (e.g., __(':attribute is not valid :name'))
                if (preg_match_all("/__\(\s*['\"](.*?)['\"]\s*,\s*\[.*?\]\)/", $contents, $matches)) {
                    foreach ($matches[1] as $match) {
                        $pattern = $this->sanitizeStringWithPlaceholders($match);
                        if (! in_array($pattern, $strings)) {
                            $strings[] = $pattern;
                        }
                    }
                }

                // Match @lang() calls with dynamic placeholders
                if (preg_match_all("/@lang\(\s*['\"](.*?)['\"]\s*,\s*\[.*?\]\)/", $contents, $matches)) {
                    foreach ($matches[1] as $match) {
                        $pattern = $this->sanitizeStringWithPlaceholders($match);
                        if (! in_array($pattern, $strings)) {
                            $strings[] = $pattern;
                        }
                    }
                }
            }
        }

        return array_unique($strings);
    }

    public function sanitizeStringWithPlaceholders($string)
    {
        // This function sanitizes strings with dynamic placeholders by removing the placeholder values
        return preg_replace_callback('/:\w+/', function ($matches) {
            return ':placeholder'; // Normalize placeholder
        }, $string);
    }
}
