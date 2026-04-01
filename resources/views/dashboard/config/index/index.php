<?php

use App\Livewire\Components\DashboardPage;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use App\ConfigItem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

new #[Title("Config")] class extends DashboardPage {
    public $path;
    public $form = [];
    public $prefix = "form";
    public $search = "";
    public function mount(string $path)
    {
        $this->path = $path;
        $this->form = ConfigItem::raw($this->path);
    }

    protected function rules()
    {
        return ConfigItem::rules($this->path, $this->prefix);
    }

    #[Computed]
    public function items()
    {
        $query = ConfigItem::all($this->path, $this->prefix);
        if (!empty($this->search)) {
            $query = $query->filter(function ($item) {
                if (!empty($this->prefix)) {
                    $key = Str::replace("{$this->prefix}.", "", $item->id);
                }
                return Str::contains($key, Str::lower($this->search));
            });
        }
        return $query;
    }

    public function updatedForm($value, $prop)
    {
        $this->validateOnly("{$this->prefix}.{$prop}");
    }
    #[Computed]
    public function fileContent()
    {
        return ConfigItem::fileContent($this->form);
    }
    public function save()
    {
        $validated = $this->validate();
        $save = $this->saveFile();
        if ($save) {
            $this->addSuccess("save", __("Saved"));
        } else {
            $this->addError("save", __("Save failed!"));
        }
        // dd($validated);
    }

    public function saveFile()
    {
        $content = $this->fileContent();
        $dir = base_path("config-edited");
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir);
        }
        $path = "{$dir}/{$this->path}.php";
        return File::put($path, $content);
    }

    public function render()
    {
        return view('dashboard::config.index.index')
            ->layout('layouts::app.sidebar', [
                'title' => Str::title($this->path),
            ]);
    }
};
