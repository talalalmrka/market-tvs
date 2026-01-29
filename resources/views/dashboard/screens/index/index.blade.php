<div class="">
    <h5>Screens</h5>
    <div class="flex items-center gap-2 mb-4">
        <button type="button" class="btn btn-primary btn-sm" wire:click="create">
            <i class="icon bi-plus-lg"></i>
            <span wire:loading.remove wire:target="create">{{ __('Create') }}</span>
            <fgx:loader wire:loading wire:target="create" />
        </button>
    </div>
    <div class="card">
        <div class="table-container">
            <table class="table table-striped table-border table-rouned table-auto xs">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>UUID</th>
                        <th>Slots</th>
                        <th>Active</th>
                        <th>Creation date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->screens as $screen)
                        <tr>
                            <td>{{ $screen->id }}</td>
                            <td>{{ $screen->name }}</td>
                            <td>{{ $screen->uuid }}</td>
                            <td>{{ $screen->timeSlots()->count() }}</td>
                            <td>{{ $screen->is_active }}</td>
                            <td>{{ $screen->created_at?->format('d M, Y') }}</td>
                            <td class="flex items-center gap-2 justify-center-safe">
                                <button type="button" class="btn btn-primary btn-xs"
                                    wire:click="edit({{ $screen->id }})">
                                    <i class="icon bi-pencil-square"></i>
                                </button>
                                <button type="button" class="btn btn-red btn-xs"
                                    wire:click="delete({{ $screen->id }})">
                                    <i class="icon bi-trash-fill"></i>
                                </button>
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
                        <th>Name</th>
                        <th>UUID</th>
                        <th>Slots</th>
                        <th>Active</th>
                        <th>Creation date</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="pt-3">
            {{ $this->screens->links() }}
        </div>
    </div>
</div>
