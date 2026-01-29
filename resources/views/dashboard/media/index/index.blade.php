<div class="">
    <h5>Media</h5>
    <div class="flex items-center gap-2 mb-4">
        <button type="button" class="btn btn-primary btn-sm" wire:click="create">
            <i class="icon bi-plus-lg"></i>
            <span wire:loading.remove wire:target="create">{{ __('Create') }}</span>
            <fgx:loader wire:loading wire:target="create" />
        </button>
    </div>
    <div class="card">
        <div class="table-container">
            <table class="table table-striped table-border table-rouned table-auto xs text-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Preview</th>
                        <th>Name</th>
                        <th>Model Type</th>
                        <th>Model Id</th>
                        <th>Collection</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Creation date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                {{-- @dump($item->original_url) --}}
                                <x-media-preview :media="$item" class="w-20 h-20" />
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->model_type }}</td>
                            <td>{{ $item->model_id }}</td>
                            <td>{{ $item->collection_name }}</td>
                            <td>{{ $item->type }}</td>
                            <td>{{ $item->humanReadableSize }}</td>
                            <td>{{ $item->created_at?->format('d M, Y') }}</td>
                            <td>
                                <div class="flex items-center gap-2 justify-center">
                                    <button type="button" class="btn btn-primary btn-xs"
                                        wire:click="edit({{ $item->id }})">
                                        <i class="icon bi-pencil-square"></i>
                                    </button>
                                    <button type="button" class="btn btn-red btn-xs"
                                        wire:click="delete({{ $item->id }})">
                                        <i class="icon bi-trash-fill"></i>
                                    </button>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No items</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Preview</th>
                        <th>Name</th>
                        <th>Model Type</th>
                        <th>Model Id</th>
                        <th>Collection</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Creation date</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="pt-3">
            {{ $this->items->links() }}
        </div>
    </div>
</div>
