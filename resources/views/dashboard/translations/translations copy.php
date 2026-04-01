<?php

use App\Livewire\Components\DashboardPage;
use App\Rules\ValidNewTranslation;
use App\Translation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

new #[Title("Translations")] class extends DashboardPage
{
    use WithPagination;

    #[Url(as: 'locale')]
    public ?string $locale = null;

    #[Url(as: 'page')]
    public int $page = 1;

    #[Url(as: 'per_page')]
    public int $perPage = 50;

    public $search = '';

    public $newTranslation = '';

    public $words = [];
    public $safeWords = [];
    public $keysMap = [];

    public function mount()
    {
        $this->authorize('manage_settings');
        $this->initWords();
    }

    public function updatedLocale()
    {
        $this->page = 1;
        $this->initWords();
    }

    public function updatedPage()
    {
        $this->initWords();
    }

    #[Computed]
    public function translations()
    {
        return Translation::all();
    }

    protected function makeSafeKey(string $original): string
    {
        return 'k_' . md5($original);
    }

    public function initWords()
    {
        $this->safeWords = [];
        $this->keysMap = [];
        $this->words = [];

        if (!$this->locale) return;

        $translation = Translation::find($this->locale);
        if (!$translation) return;

        $paginator = $translation->paginate(20, null, $this->page);

        $assoc = collect($paginator->items())
            ->mapWithKeys(fn($w) => [$w['key'] => $w['value']])
            ->toArray();

        $this->words = $assoc;

        foreach ($assoc as $origKey => $value) {
            $safe = $this->makeSafeKey($origKey);
            $this->keysMap[$safe] = $origKey;
            $this->safeWords[$safe] = $value;
        }
    }

    #[Computed]
    public function wordsPaginator()
    {
        if (!$this->locale) return null;

        return Translation::find($this->locale)
            ?->paginate(20, null, $this->page);
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

            $sync = $translation->syncWords($override);

            // rebuild safeWords / keysMap from the updated translation
            $this->initWords();

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
                    $this->safeWords = [];
                    $this->keysMap = [];
                    $this->words = [];
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
            $this->validate([
                'safeWords' => ['nullable', 'array'],
                'safeWords.*' => ['nullable', 'string', 'max:10000'],
            ]);
            $translation = Translation::find($this->locale);
            if (! $translation) {
                $this->addError('save', __('Translation :locale not found!', ['locale' => $this->locale]));
                return;
            }
            // Map back to original keys
            $mapped = [];
            foreach ($this->safeWords as $safe => $val) {
                $orig = $this->keysMap[$safe] ?? $safe;
                $mapped[$orig] = $val;
            }
            $save = $translation->save($mapped);
            if ($save) {
                $this->addSuccess('save', __('Saved.'));
                $this->initWords();
            } else {
                $this->addError('save', __('Save failed!'));
            }
        } catch (\Exception $e) {
            $this->addError('save', $e->getMessage());
        }
    }
};
