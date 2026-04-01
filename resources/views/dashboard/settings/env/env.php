<?php

use App\Livewire\Components\DashboardPage;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Environment variables')] class extends DashboardPage
{
    public $path;
    public $env = '';
    public function mount()
    {
        $this->path = base_path('.env');
        if (File::exists($this->path)) {
            $this->env = file_get_contents($this->path);
        }
    }
    public function rules()
    {
        return [
            'env' => ['nullable', 'string'],
        ];
    }
    public function save()
    {
        $this->validate();
        $save = File::put($this->path, $this->env);
        if ($save) {
            $this->status(__('Saved successfully'));
        } else {
            $this->addError('status', __('Save failed!'));
        }
    }
};
