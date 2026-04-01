<?php

use App\Livewire\Components\DashboardPage;
use App\Rules\ValidNewTranslation;
use App\Translation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

new #[Title('Translations')] class extends DashboardPage
{
    public ?Translation $translation;

    #[Url(as: 'locale')]
    public ?string $locale = null;

    public int $translationsPerPage = 15;

    public $translationsSearch = '';

    public int $wordsPerPage = 50;

    public $wordsSearch = '';

    public $newTranslation = '';

    public $words = [];

    public $form = [];

    // public $safeWords = [];
    // public $keysMap = [];

    public function mount()
    {
        $this->authorize('manage_settings');
        // $this->initWords();
        $this->loadTranslation();
    }

    #[Computed]
    public function translations()
    {
        return Translation::paginate($this->translationsPerPage, $this->translationsSearch, 'translations_page');
    }

    /* #[Computed]
    public function getWords()
    {
        return $this->translation?->wordsPaginate($this->wordsPerPage, $this->wordsSearch, null, 'words_page');
    } */
    public function updatedLocale()
    {
        $this->loadTranslation();
    }

    public function loadTranslation()
    {
        if (empty($this->locale)) {
            $this->translation = null;
            $this->words = [];
            $this->form = [];

            return;
        }
        $translation = Translation::find($this->locale);
        if (! $translation) {
            $this->translation = null;
            $this->words = [];
            $this->form = [];

            return;
        }
        $this->translation = $translation;
        $this->words = $this->translation->words->toArray();
        $this->form = [];

        $this->translation->words->each(function ($value, $key) {
            $this->form[] = [
                'label' => $key,
                'value' => $value,
            ];
        });
    }

    /**
     * Generate a deterministic safe key for binding.
     */
    protected function makeSafeKey(string $original): string
    {
        return 'k_'.md5($original);
    }

    /**
     * initialize translations (build safeWords and keysMap)
     *
     * @return void
     */
    public function initWords()
    {
        /* $this->safeWords = [];
        $this->keysMap = [];
        $this->words = [];
        if (empty($this->locale)) {
            return;
        }
        $translation = Translation::find($this->locale);
        if (! $translation) {
            return;
        }
        $raw = $translation->words;
        $assoc = [];
        if ($raw instanceof \Illuminate\Support\Collection) {
            if ($raw->isEmpty()) {
                $assoc = [];
            } else {
                $first = $raw->first();
                if (is_array($first)) {
                    if (isset($first['key']) && array_key_exists('value', $first)) {
                        $assoc = $raw->mapWithKeys(fn($w) => [$w['key'] => $w['value']])->toArray();
                    } else {
                        $assoc = $raw->toArray();
                    }
                } elseif ($first instanceof \Illuminate\Database\Eloquent\Model) {
                    if (isset($first->key) && (isset($first->value) || isset($first->translation))) {
                        $assoc = $raw->mapWithKeys(function ($w) {
                            $val = $w->value ?? ($w->translation ?? '');
                            return [$w->key => $val];
                        })->toArray();
                    } else {
                        $assoc = $raw->toArray();
                    }
                } else {
                    $assoc = $raw->toArray();
                }
            }
        } elseif (is_array($raw)) {
            $assoc = $raw;
        } else {
            $assoc = [];
        }
        $this->words = $assoc;
        foreach ($assoc as $origKey => $value) {
            $safe = $this->makeSafeKey((string) $origKey);
            $this->keysMap[$safe] = (string) $origKey;
            $this->safeWords[$safe] = is_scalar($value) ? $value : (string) json_encode($value);
        } */
    }

    /**
     * sync translations
     */
    public function sync($locale)
    {
        $this->authorize('manage_settings');

        $translation = Translation::find($locale);
        if ($translation) {
            $isCurrent = $this->locale === $locale;
            $override = $isCurrent ? $this->words : [];

            $translation->syncWords($override);

            // rebuild safeWords / keysMap from the updated translation
            $this->loadTranslation();

            $this->toastSuccess(__('Sync translations for :locale done.', ['locale' => $locale]));
        } else {
            $this->toastError(__('Translation :locale not found!', ['locale' => $locale]));
        }
    }

    public function create()
    {
        $this->authorize('manage_settings');

        $this->validate([
            'newTranslation' => ['required', 'string', new ValidNewTranslation],
        ]);

        $newTranslation = $this->newTranslation;
        // reset field
        $this->newTranslation = '';

        try {
            $create = Translation::create($newTranslation);
            if ($create) {
                $this->locale = $newTranslation;
                $this->initWords();
                $this->addSuccess('create', __('Created.'));
            } else {
                $this->addError('create', __('Create failed!'));
            }
        } catch (\Exception $e) {
            $this->addError('create', $e->getMessage());
        }
    }

    public function edit($locale)
    {
        $this->authorize('manage_settings');
        $this->locale = $locale;
        $this->initWords();
    }

    public function delete($locale)
    {
        $this->authorize('manage_settings');
        $translation = Translation::find($locale);
        if ($translation) {
            $delete = $translation->delete();
            if ($delete) {
                if ($this->locale === $locale) {
                    $this->locale = null;
                    $this->words = collect();
                    $this->form = [];
                }
                $this->toastSuccess(__('Deleted :locale success.', ['locale' => $locale]));
            } else {
                $this->toastError(__('Delete :locale failed!', ['locale' => $locale]));
            }
        } else {
            $this->toastError(__('Translation :locale not found!', ['locale' => $locale]));
        }
    }

    public function save()
    {
        try {
            $this->authorize('manage_settings');
            $validated = $this->validate([
                'form' => ['nullable', 'array'],
                'form.*' => ['nullable', 'array'],
                'form.*.label' => ['required', 'string', 'max:10000'],
                'form.*.value' => ['nullable', 'string', 'max:10000'],
            ]);
            // dd($validated);
            $translation = Translation::find($this->locale);
            if (! $translation) {
                $this->addError('save', __('Translation :locale not found!', ['locale' => $this->locale]));

                return;
            }
            // Map back to original keys
            $mapped = [];
            foreach ($this->form as $item) {
                $label = data_get($item, 'label', '');
                $value = data_get($item, 'value', '');
                if (! empty($label)) {
                    $mapped[$label] = $value;
                }
            }
            // dd($mapped);
            $save = $translation->save($mapped);
            if ($save) {
                $this->addSuccess('save', __('Saved.'));
                $this->loadTranslation();
            } else {
                $this->addError('save', __('Save failed!'));
            }
        } catch (\Exception $e) {
            $this->addError('save', $e->getMessage());
        }
    }
};
