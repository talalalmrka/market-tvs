<?php

namespace App;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Livewire\Wireable;
use Symfony\Component\Finder\SplFileInfo;

class Translation implements Arrayable, Wireable
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

        return collect($files)
            ->filter(fn (SplFileInfo $file) => $file->getType() === 'file' && $file->getExtension() === 'json');
    }

    /**
     * all translations
     *
     * @return \Illuminate\Support\Collection
     */
    public static function all()
    {
        return self::allFiles()
            ->map(fn (SplFileInfo $file) => self::find($file->getFilenameWithoutExtension()))
            ->filter(fn ($l) => ! empty($l))
            ->sortKeys();
    }

    /**
     * Search translations by locale (LIKE)
     *
     * @param  string|null  $query
     * @return \Illuminate\Support\Collection
     */
    public static function search(?string $search)
    {
        $collection = self::all();

        if (empty($search)) {
            return $collection;
        }

        $search = strtolower($search);

        return $collection->filter(function ($translation) use ($search) {
            return str_contains(strtolower($translation->locale), $search);
        })->values();
    }

    /**
     * Paginate translations collection
     *
     * @param  int  $perPage
     * @param  string|null  $pageName
     * @param  int|null  $page
     * @param  int|null  $total
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function paginate($perPage = 15, $search = null, $pageName = null, $page = null, $total = null)
    {
        $pageName = $pageName ?? 'page';

        $page = $page ?: (Paginator::resolveCurrentPage($pageName) ?: 1);

        $collection = ! empty($search) ? self::search($search)->values() : self::all()->values(); // reset keys

        $total = $total ?? $collection->count();

        $results = $collection->forPage($page, $perPage);

        return new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
    }

    public function wordsSearch(?string $search)
    {
        $collection = $this->words;

        if (empty($search)) {
            return $collection;
        }

        $search = strtolower($search);

        return $collection->filter(function ($value, $key) use ($search) {
            return str_contains(strtolower($key), $search);
        });
    }

    /**
     * Paginate locale words
     *
     * @param  int|null  $perPage
     * @param  string|null  $search
     * @param  string|null  $status  null|completed|pending
     * @param  string|null  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function wordsPaginate($perPage = null, $search = null, $status = null, $pageName = null, $page = null)
    {
        $perPage = $perPage ?? 50;
        $pageName = $pageName ?? 'page';

        $page = $page ?: (Paginator::resolveCurrentPage($pageName) ?: 1);

        $collection = ! empty($search) ? $this->wordsSearch($search) : $this->words;

        // Apply status filter
        if (! empty($status)) {
            if ($status === 'completed') {
                $collection = $collection->filter(fn ($t) => ! empty(trim($t)));
            }

            if ($status === 'pending') {
                $collection = $collection->filter(fn ($t) => empty(trim($t)));
            }
        }

        $total = $collection->count();

        // Slice while preserving keys
        $results = $collection->slice(($page - 1) * $perPage, $perPage, true);

        return new LengthAwarePaginator(
            $results,
            $total,
            $perPage,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => $pageName,
            ]
        );
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

    public function toArray()
    {
        return [
            'locale' => $this->locale,
            'words' => $this->words,
        ];
    }

    public function toLivewire()
    {
        return [
            'locale' => $this->locale,
            'words' => $this->words->toArray(),
        ];
    }

    public static function fromLivewire($data)
    {
        $locale = data_get($data, 'locale');
        $words = data_get($data, 'words');

        return new static($locale, $words);
    }
}
