<?php

use App\Livewire\Components\DashboardPage;
use App\Models\MenuItem;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Title('Select Model')] class extends DashboardPage
{
    public ?MenuItem $item;
    #[Url(as: 'item')]
    public ?int $itemId = null;
    public $type = null;
    public $model_type = null;
    public $model_id = null;
    public $url = null;
    public function mount()
    {
        $this->loadItem();
    }
    public function updatedItemId()
    {
        $this->loadItem();
    }
    protected function loadItem()
    {
        if (!empty($this->itemId)) {
            $item = MenuItem::find($this->itemId);
            if ($item && $item instanceof MenuItem) {
                $this->item = $item;
                $this->fill($item->only('type', 'model_type', 'model_id', 'url'));
                // $this->model_type = $item->model_type;
                // $this->model_id = $item->model_id;
                // $this->url = $item->model_id;
            } else {
                $this->item = null;
                $this->reset('type', 'model_type', 'model_id', 'url');
                // $this->model_type = null;
                // $this->model_id = null;
            }
        } else {
            $this->item = null;
            // $this->model_type = null;
            // $this->model_id = null;
            $this->reset('type', 'model_type', 'model_id', 'url');
        }
    }


    protected function types(): Collection
    {
        return collect(menu_item_types());
    }

    #[Computed()]
    public function modelData(): ?array
    {
        if (empty($this->type)) {
            return null;
        }
        return $this->types()->firstWhere('value', $this->type);
    }

    #[Computed()]
    protected function modelType()
    {
        return data_get($this->modelData(), 'model_type');
    }

    #[Computed()]
    protected function searchCols()
    {
        return data_get($this->modelData(), 'searchCols', []);
    }

    #[Computed()]
    protected function args()
    {
        return data_get($this->modelData(), 'args', []);
    }
};
