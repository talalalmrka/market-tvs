<?php

use App\Livewire\Components\DashboardPage;
use App\Models\MenuItem;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;

new #[Title('Select Model')] class extends DashboardPage
{
    public ?MenuItem $item = null;

    #[Url(as: 'item')]
    public ?int $itemId = null;

    public $form = [];

    public function mount()
    {
        $this->loadItem();
    }

    public function updatedItemId()
    {
        $this->loadItem();
    }

    /*public function updatedForm($value, $property)
    {
        $find = ".type";
        if (Str::endsWith($property, $find)) {
            $limit = Str::length($property) - Str::length($find);
            $prefix = Str::limit($property, $limit, '');
            $modelTypeKey = "form.{$prefix}.model_type";
            $modelIdKey = "form.{$prefix}.model_id";
            if ($value === 'custom') {
                $this->fill([
                    $modelTypeKey => null,
                    $modelIdKey => null,
                ]);
            } else {
                $morph = model_property($value, 'morph');
                $this->fill([
                    $modelTypeKey => $morph,
                    $modelIdKey => null,
                ]);
            }
        }
    }*/
    protected function loadItem()
    {
        if (empty($this->itemId)) {
            $this->item = null;
            $this->form = [];

            return;
        }
        $item = MenuItem::find($this->itemId);
        if ($item && $item instanceof MenuItem) {
            $this->item = $item;
            $this->form = $this->item->toArray();
        } else {
            $this->item = null;
            $this->form = [];

            return;
        }
    }
};
