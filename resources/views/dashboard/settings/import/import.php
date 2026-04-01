<?php

use App\Livewire\Components\DashboardPage;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Title('Import/Export settings')] class extends DashboardPage
{
    public $exportData = '';
    #[Validate()]
    public $importData = '';
    public function mount()
    {
        $this->initExportData();
    }
    public function initExportData()
    {
        $settings = Setting::all();
        $data = $settings->map(fn(Setting $setting) => $setting->only(['key', 'value', 'type']))->toArray();
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $this->exportData = base64_encode($json);
    }
    public function rules()
    {
        return [
            'importData' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $json = base64_decode($value, true);
                    if ($json === false || !is_json($json)) {
                        $fail(__('Import data is invalid!'));
                        return;
                    }
                    $data = json_decode($json, true);
                    $settings = collect($data)->filter(function ($item) {
                        return !empty(data_get($item, 'type')) && !empty(data_get($item, 'key'));
                    });
                    if (!$settings->count()) {
                        $fail(__('Import data does not contains valid settings!'));
                    }
                },
            ],
        ];
    }
    public function getImportSettings(): Collection
    {
        $json = base64_decode($this->importData);
        if (is_json($json)) {
            $data = json_decode($json, true);
            return collect($data)->filter(fn($item) => !empty(data_get($item, 'type')) && !empty(data_get($item, 'key')));
        }
        return collect();
    }
    #[Computed()]
    public function preview(): array
    {
        return $this->getImportSettings()->toArray();
    }
    public function import()
    {
        $this->validate();
        $settings = $this->getImportSettings();
        $success = 0;
        $errors = 0;
        $settings->each(function ($setting) use (&$success, &$errors) {
            $key = data_get($setting, 'key');
            $type = data_get($setting, 'type');
            $value = data_get($setting, 'value');
            $update = update_option($key, $value, $type);
            if ($update) {
                $success++;
            } else {
                $errors++;
            }
        });
        $this->addSuccess('import', __('Import done success :success, errors :errors', ['success' => $success, 'errors' => $errors]));
    }
};
