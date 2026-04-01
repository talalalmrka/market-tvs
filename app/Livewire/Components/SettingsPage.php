<?php

namespace App\Livewire\Components;

use App\Models\Setting;
use App\Traits\HasMediaProperties;
use App\Traits\WithToast;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app.sidebar')]
abstract class SettingsPage extends Component
{
    use HasMediaProperties, WithToast;

    public function mount()
    {
        $this->authorize('manage_settings');
        if (method_exists($this, 'beforeMount')) {
            $this->beforeMount();
        }
        $this->fillSettings();
        if (method_exists($this, 'afterMount')) {
            $this->afterMount();
        }
    }

    public function beforeFill() {}

    public function afterFill() {}

    public function getSettingValue($key)
    {
        return get_option($key, get_default_option($key));
    }
    /**
     * The prefix like app, mail
     * @return string|null
     */
    public function prefix()
    {
        return null;
    }
    public function getSettingKey($prop)
    {
        $prefix = $this->prefix();
        return !empty($prefix) ? "{$prefix}.{$prop}" : $prop;
    }
    public function fillSettings()
    {
        if (method_exists($this, 'beforeFill')) {
            $this->beforeFill();
        }
        foreach ($this->all() as $key => $value) {
            $settingKey = $this->getSettingKey($key);
            $type = get_option_type($settingKey);
            if ($type !== 'file') {
                $this->{$key} = get_option($settingKey, get_default_option($settingKey));
            }
        }
        if (method_exists($this, 'afterFill')) {
            $this->afterFill();
        }
    }

    public function updated($property, $value)
    {
        $this->validateOnly($property);
    }

    #[Computed]
    public function getPreviews(string $prop)
    {
        $settingKey = $this->getSettingKey($prop);
        $setting = Setting::withKey($settingKey);
        $temp = data_get($this, $prop);
        return $setting?->getPreviews($temp) ?? [];
    }
    #[Computed()]
    /**
     * get rules
     * @return array|null
     */
    public function getRules()
    {
        return method_exists($this, 'rules')
            ? $this->rules()
            : null;
    }
    public function save()
    {
        $this->authorize('manage_settings');
        if (method_exists($this, 'beforeSave')) {
            $this->beforeSave();
        }
        $validated = $this->validate();
        foreach ($validated as $key => $value) {
            $settingKey = $this->getSettingKey($key);
            $type = get_option_type($settingKey);
            $save = update_option($settingKey, $value, $type);
            if ($save) {
                if ($type === 'file') {
                    $this->reset($key);
                }
            } else {
                $this->addError($key, __('Save failed!'));
            }
        }
        $this->status(__('Saved.'));
        if (method_exists($this, 'afterSave')) {
            $this->afterSave();
        }
    }

    public function resetSettings()
    {
        $this->authorize('manage_settings');
        try {
            DB::table('settings')->truncate();
            Artisan::call('db:seed', [
                '--class' => \Database\Seeders\SettingSeeder::class,
                '--force' => true,            // run in production
            ]);
            $this->skipRender();
            $this->addSuccess('reset', __('Settings reseted.'));

            $this->js('refresh');
        } catch (Exception $e) {
            $this->addError('reset', $e->getMessage());
        }
    }
    /*abstract public function title();
    abstract public function view();
    public function render()
    {
        return $this->view()->layout('layouts::app.sidebar', [
            'title' => $this->title(),
        ]);
    }*/
}
