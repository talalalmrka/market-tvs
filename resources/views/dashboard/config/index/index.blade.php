<form wire:submit="save" class="z-1">
    <x-card>
        <div class="flex items-center gap-2 mb-3">
            <fgx:input type="search" wire:model.live="search" id="search" :placeholder="__('Search...')"
                start-icon="bi-search" class="xs pill w-60" />
        </div>
        @forelse ($this->items as $index => $item)
            {{ $item->render([
                'wire:key' => "item.{$index}",
            ]) }}
        @empty
            <div class="alert alert-soft alert-info sm">No items</div>
        @endforelse

    </x-card>
    <x-card title="Form" class="mt-6">
        @pre($form, 'pre-100')
    </x-card>
    <x-card title="Items" class="mt-6">
        @pre($this->items, 'pre-100')
    </x-card>
    <x-card title="File Content" class="mt-6">
        <pre class="pre-100">{{ $this->fileContent }}</pre>
    </x-card>

    <div class="sticky bottom-0 flex items-center gap-2 justify-between p-2">
        <div>
            <button
                type="submit"
                class="btn btn-sm btn-primary flex items-center gap-1">
                <i class="icon bi-floppy"></i>
                <span wire:loading.remove wire:target="save">{{ _('Save') }}</span>
                <i wire:loading wire:target="save" class="icon fg-loader-dots-move"></i>
            </button>
        </div>
        <div>
            <fgx:status id="save" />
        </div>
    </div>
</form>
