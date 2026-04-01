<div {{ $attributes }}>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Menu item</h5>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 gap-4">
                <div class="col">
                    <fgx:select id="itemId" wire:model.live="itemId" :label="__('Select item')"
                        :options="menu_item_options(__('Select item'))" />
                </div>
                @if ($item)
                @endif

            </div>
        </div>
    </div>
    <div class="mt-6">
        @dump($this->all())
        @dump(menu_item_type_options())
    </div>
    @if ($item)
        <div class="card mt-6 overflow-visible">
            <div class="card-header">
                <h5 class="card-title">Edit item</h5>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 gap-3">
                    <div class="col">
                        <fgx:select id="type" wire:model.live="type" :label="__('Type')"
                            :options="menu_item_type_options()" />
                    </div>
                    @if (empty($model_type))
                        <div class="col">
                            <fgx:input id="url" wire:model.live="url" :label="__('Url')" />
                        </div>
                    @else
                        <div class="col">
                            <livewire:components::select-model wire:key="{{ $this->modelType }}"
                                wire:model.live="model_id"
                                :model="$this->modelType"
                                :search-cols="$this->searchCols" :args="$this->args" />
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif


</div>
