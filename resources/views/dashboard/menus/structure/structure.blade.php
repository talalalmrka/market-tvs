<form wire:submit="save">
    <div x-data="MenuItemsAccordion" {{ $attributes->merge([
        'class' => css_classes(['card']),
    ]) }}>
        <div class="card-header flex items-center gap-2">
            <div class="card-title text-primary flex-1 flex items-center gap-2">
                <i class="icon bi-list-nested"></i>
                <span>{{ __('Structure (:name)', ['name' => $menu->name]) }}</span>
            </div>
            <button x-on:click="toggleAll" type="button">
                <i class="icon bi-arrows-collapse" :class="{ 'text-primary': allOpen }"></i>
            </button>
        </div>
        <div class="card-body">
            @if (!empty($items))
                <div class="max-h-screenn overflow-autoo">
                    <x-menu-tree
                        :items="$items"
                        :parentId="null"
                        input-key="items" />
                </div>
            @else
                <fgx:alert soft :content="__('No items')" />
            @endif
            @if ($errors->has('items.*'))
                @dump($errors->only('items.*'))
                <ul class="alert alert-soft-red">
                    @foreach ($errors as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="card-footer flex items-center gap-2 justify-between">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="icon bi-floppy"></i>
                <span wire:loading.remove wire:target="save">{{ __('Save changes') }}</span>
                <fgx:loader wire:loading wire:target="save" />
            </button>
            <fgx:status id="save" class="alert-soft xs" />
        </div>
    </div>
    <div x-cloak x-data="{ open: false }" class="sticky bottom-0 flex flex-col">
        <button x-on:click="open = !open" type="button" class="btn-circle m-2"
            :class="{ 'btn-circle-primary': open, 'btn-circle-secondary': !open }">
            <i class="icon bi-terminal"></i>
        </button>
        <div x-show="open" class="flex-1 max-h-90 overflow-auto">
            <pre id="items-pre" class="sf-dump">
            </pre>
        </div>
    </div>
</form>
