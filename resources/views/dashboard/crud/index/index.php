<?php

use App\Livewire\Components\DashboardPage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;

new #[Title('Crud Index')] class extends DashboardPage
{
    public $search = '';

    #[Computed()]
    public function items()
    {
        $items = models()->map(fn ($model) => [
            'id' => data_get($model, 'id'),
            'label' => data_get($model, 'plural'),
            'icon' => data_get($model, 'icon'),
            'table' => data_get($model, 'table'),
            'url' => route('dashboard.crud.item', data_get($model, 'table')),
        ]);
        if ($this->search) {
            $items = $items->filter(fn ($item) => str($item['label'])->contains($this->search, true));
        }

        return $items->sortBy('label');
    }
};
