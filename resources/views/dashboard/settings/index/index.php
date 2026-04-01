<?php

use App\ConfigItem;
use App\Livewire\Components\DashboardDatatable;
use App\Models\Setting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

new #[Title('Settings')] class extends DashboardDatatable
{
    public $type = null;
    public $category = null;

    public function builder()
    {
        $query = Setting::query();
        if (!empty($this->type)) {
            $query->where('type', $this->type);
        }
        if (!empty($this->category)) {
            $query->where('key', 'like', "{$this->category}.%");
        }

        return $query;
    }

    public function getColumns()
    {
        return [
            column('type')
                ->label(__('Type'))
                ->sortable()
                ->searchable()
                ->filterable(),
            column('key')
                ->label(__('Key'))
                ->sortable()
                ->searchable()
                ->filterable(),
            column('value')
                ->label(__('Value'))
                ->sortable()
                ->searchable()
                ->filterable()
                ->content(function (Setting $setting) {
                    return $this->valueContent($setting);
                    // return resolve_option_value($setting);
                }),
        ];
    }

    public function valueContent(Setting $setting)
    {
        if (is_array($setting->value) || is_object($setting->value)) {
            return json_encode($setting->value);
        }
        if (filter_var($setting->value, FILTER_VALIDATE_URL)) {
            return view('components.link', [
                'href' => $setting->value,
                'target' => '_blank',
            ]);
        }
        if ($setting->type === 'file') {
            return container([
                'class' => 'flex flex-wrap gap-4',
                'content' => contents($setting->getMedia($setting->key)->map(fn(Media $media) => view('dashboard::media.preview', ['media' => $media]))),
            ]);
        }

        return $setting->value;
    }

    public function edit($id)
    {
        $this->dispatch('edit', 'setting', $id);
    }

    public function create()
    {
        $this->dispatch('edit', 'setting');
    }

    #[Computed()]
    public function filtersView()
    {
        return view('dashboard::settings.filters', [
            'typeOptions' => [
                ...[
                    [
                        'label' => __('All'),
                        'value' => null,
                    ],
                ],
                ...setting_type_options(),
            ],
            'categoryOptions' => ConfigItem::categoryOptions(__('All')),
        ]);
    }

    /*public function render()
    {
        return view('dashboard::settings.index.index', [
            'builder' => $this->builder(),
        ])->layout('layouts.dashboard', [
            'title' => __('Settings'),
        ]);
    }*/
};
