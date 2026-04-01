<?php

use App\Livewire\Components\DashboardDatatable;
use Livewire\Attributes\Title;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

new #[Title('Media')] class extends DashboardDatatable
{
    public ?Media $currentMedia;
    public bool $showModal = false;
    public function builder()
    {
        return Media::query();
    }

    public function getColumns()
    {
        return [
            column('preview')
                ->label(__('Preview'))
                ->content(function (Media $media) {
                    return view('dashboard::media.preview', ['media' => $media]);
                }),
            column('name')
                ->label(__('Details'))
                ->sortable()
                ->searchable()
                ->filterable()
                ->content(function (Media $media) {
                    return view('dashboard::media.details', ['media' => $media]);
                }),
        ];
    }
    public function updatedShowModal($value)
    {
        if (!$value) {
            $this->currentMedia = null;
        }
    }
    public function details(Media $media)
    {
        $this->currentMedia = $media;
        $this->showModal = true;
    }
};
