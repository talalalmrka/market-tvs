<?php

use App\Livewire\Components\DashboardDatatable;
use Livewire\Attributes\Title;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

new #[Title('Media')] class extends DashboardDatatable
{
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
};
