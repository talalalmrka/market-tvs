<?php

use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

new class extends Component
{
    use WithPagination;

    #[Computed()]
    public function items()
    {
        return Media::paginate();
    }

    public function delete(Media $media)
    {
        $media->delete();
    }
};
