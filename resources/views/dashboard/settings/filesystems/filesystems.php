<?php

use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Renderless;
use App\Livewire\Components\SettingsPage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

new #[Title('Filesystems settings')] class extends SettingsPage
{
    public $default;
    public $links = [];
    public $disks = [];
    public function mount()
    {
        parent::mount();
        $this->fillLinks();
    }
    public function prefix()
    {
        return 'filesystems';
    }
    protected function rules()
    {
        return [
            'default' => [
                'required',
                'string',
                Rule::in(
                    array_keys($this->disks)
                ),
            ],
            'links' => [
                'required',
                'array'
            ],
            'links.*.target' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!File::isDirectory($value)) {
                        $fail("The $attribute is not valid directory!");
                    }
                },
            ],
            'links.*.path' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!File::isDirectory($value)) {
                        $fail("The $attribute is not valid directory!");
                    }
                },
            ],
            'disks' => [
                'required',
                'array'
            ],
            ...config_rules($this->disks, 'disks'),
        ];
    }

    #[Computed()]
    public function diskOptions()
    {
        return collect($this->disks)
            ->map(fn($disk, $key) => [
                'label' => str($key)->title()->value(),
                'value' => $key,
            ])->values()
            ->toArray();
    }
    #[Computed()]
    public function getRules()
    {
        return $this->rules();
    }

    public function fillLinks()
    {
        $this->links = collect($this->links)
            ->map(fn($path, $target) => [
                'target' => $target,
                'path' => $path,
            ])->values()
            ->toArray();
    }

    public function removeLink(int $index)
    {
        unset($this->links, $index);
    }
    public function addLink()
    {
        $this->links[] = [
            'src' => '',
            'dist' => '',
        ];
    }
    public function getValidatedLinks()
    {
        $links = [];
        collect($this->links)->each(function ($link) use (&$links) {
            $target = data_get($link, 'target');
            $path = data_get($link, 'path');
            $links[$target] = $path;
        });
        return $links;
    }

    #[Computed]
    public function symlinks()
    {
        return collect($this->links)->map(function ($link) {
            $target = data_get($link, 'target'); // target
            $path = data_get($link, 'path'); // symlink path
            $isLink = is_link($target);
            return [
                'path' => $path,
                'target' => $target,
                'path_exists' => File::exists($path),
                'target_exists' => File::exists($target) || $isLink,
                'is_link' => $isLink,
                'linked' => $isLink
                    ? realpath(readlink($target)) === realpath($path)
                    : false,
            ];
        })->values();
    }

    #[Renderless]
    public function linkStorage()
    {
        Artisan::call('storage:link');
        $output = Artisan::output();
        $this->stream(
            to: 'output',
            content: $output,
            replace: false,
        );
    }

    #[Renderless]
    public function unlinkStorage()
    {
        Artisan::call('storage:unlink');
        $output = Artisan::output();
        $this->stream(
            to: 'output',
            content: $output,
            replace: false,
        );
    }



    public function save()
    {
        $this->authorize('manage_settings');
        $validated = $this->validate();
        $validated['links'] = $this->getValidatedLinks();
        foreach ($validated as $key => $value) {
            $settingKey = $this->settingKey($key);
            $type = get_option_type($settingKey);
            $save = update_option($settingKey, $value, $type);
            if ($save) {
                if ($type === 'file') {
                    $this->reset($settingKey);
                }
            } else {
                $this->addError($settingKey, __('Save failed!'));
            }
        }
        $this->status(__('Saved.'));
    }
};
