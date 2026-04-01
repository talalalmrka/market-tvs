<?php

use App\Livewire\Components\DashboardPage;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use App\Models\Setting;
use Illuminate\Support\Facades\File;

new #[Title("Settings")] class extends DashboardPage {
    public $path;
    public $form = [];
    public $prefix = "form";
    public $search = "";
    public function mount(string $path)
    {
        $this->path = $path;
        $this->loadForm();
    }
    protected function loadForm()
    {
        // $this->form = config_seeder($this->path);
        $this->settings()->each(function (Setting $setting) {
            data_set($this->form, $setting->key, $setting->value);
        });
    }
    protected function rules()
    {
        //return config_rules(raw_config($this->path), "{$this->prefix}.{$this->path}");
        $rules = [
            'form' => [
                'required',
                'array',
            ],
        ];
        collect(arr_flat(raw_config($this->path), "{$this->prefix}.{$this->path}", true))->each(function ($value, $key) use (&$rules) {
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

    #[Computed]
    public function getRules()
    {
        return $this->rules();
    }
    #[Computed]
    public function settings()
    {
        $query = Setting::withPath($this->path);
        if (!empty($this->search)) {
            $query->where(function ($q) {
                foreach (["type", "key", "value"] as $col) {
                    $q->orWhere($col, "like", "%{$this->search}%");
                }
            });
        }
        return $query->get();
    }
    #[Computed]
    public function fields()
    {
        $fields = collect(arr_flat($this->form, null, true));
        if (!empty($this->search)) {
            $fields = $fields->filter(function ($val, $key) {
                return str($key)->contains($this->search, true);
            });
        }
        return $fields;
    }

    public function updatedForm($value, $prop)
    {
        $this->validateOnly("form.{$prop}");
    }
    public function resetField(string $key)
    {
        data_set($this->form, $key, config_real($key));
    }
    public function save()
    {
        $this->authorize('manage_settings');

        $validated = $this->validate();
        $data = arr_flat($validated[$this->prefix], null, true);
        // dd($data);
        foreach ($data as $key => $value) {
            $type = get_option_type($key);
            $save = update_option($key, $value, $type);
            if ($save) {
                if ($type === 'file') {
                    $this->reset("{$this->prefix}.{$key}");
                }
            } else {
                $this->addError("{$this->prefix}.{$key}", __('Save failed!'));
            }
        }
        $this->status(__('Saved.'));
    }
    public function savee()
    {
        $validated = $this->validate();
        $data = arr_flat($validated, null, true);
        dd($data);
        $save = $this->saveFile();
        if ($save) {
            $this->addSuccess("save", __("Saved"));
        } else {
            $this->addError("save", __("Save failed!"));
        }
        // dd($validated);
    }
    public function render()
    {
        $title = str("{$this->path} settings")->title()->value();
        return view("dashboard::settings.page.page")->layout(
            "layouts::app.sidebar",
            [
                "title" => $title,
            ]
        );
    }
};
