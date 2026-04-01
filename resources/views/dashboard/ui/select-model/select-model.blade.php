<div {{ $attributes }}>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Menu item</h5>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 gap-4">
                <div class="col">
                    <fgx:select id="itemId" wire:model.live="itemId" :label="__('Select item')" class="sm"
                        :options="menu_item_options(__('Select item'))" />
                </div>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
        <div class="col">
            @dump($form)
        </div>
        <div class="col md:col-span-2">
            @if ($item)
                <div class="card mt-6 overflow-visible">
                    <div class="card-header">
                        <h5 class="card-title">{{ __('Edit (:name)', ['name' => $item->name]) }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="grid grid-cols-1 gap-3">
                            <div class="col">
                                <fgx:input wire:model.live="form.name" class="sm"
                                    id="form.name" :label="__('Name')" />
                            </div>
                            <div class="col">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="col">
                                        <fgx:icon-picker id="form.icon"
                                            wire:model.live="form.icon" :label="__('Icon')"
                                            :value="data_get($form, 'icon')" size="sm" />
                                    </div>
                                    <div class="col">
                                        <fgx:input wire:model.live="form.title" class="sm"
                                            id="form.title" :label="__('Title')" />
                                    </div>
                                    <div class="col">
                                        <fgx:input wire:model.live="form.class_name"
                                            class="sm"
                                            id="form.class_name" :label="__('CSS Class')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                @php
                                    // $type = data_get($form, 'data.type');
                                @endphp
                                <livewire:components::select-permalink
                                    {{-- wire:key="{{ model_property($type, 'id') }}" --}}
                                    wire:model.live="form" class="sm" />
                            </div>
                            {{-- <div class="col">
                                <fgx:select id="form.type" wire:model.live="form.type" class="sm"
                                    :label="__('Type')"
                                    :options="menu_item_type_options()" />
                            </div>
                            @php
                                $type = data_get($form, 'data.type');
                            @endphp
                            @if ($type === 'custom')
                                <div class="col">
                                    <fgx:input id="form.url" wire:model.live="form.url" class="sm"
                                        :label="__('Url')" />
                                </div>
                            @else
                                <div class="col">
                                    <livewire:components::select-model class="sm"
                                        wire:key="{{ model_property($type, 'id') }}"
                                        wire:model.live="form.model_id"
                                        :model="model_property($type, 'morph')"
                                        :search-cols="model_property($type, 'search_cols')" :args="model_property($type, 'args')" :label="model_property($type, 'select_label')" :placeholder="model_property($type, 'select_label')" />
                                </div>
                            @endif --}}
                            <div class="col">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                    <div class="col">
                                        <fgx:switch id="form.new_tab"
                                            wire:model.live="form.new_tab"
                                            :label="__('Open in new tab')"
                                            :checked="boolval(data_get($form, 'new_tab'))" />
                                    </div>
                                    <div class="col">
                                        <fgx:switch id="form.navigate"
                                            wire:model.live="form.navigate"
                                            :label="__('Wire navigate')"
                                            :checked="boolval(data_get($form, 'navigate'))" />
                                    </div>
                                    <div class="col">
                                        <button wire:click="deleteItem({{ data_get($form, 'id') }})" type="button"
                                            class="btn btn-outline-red btn-sm">
                                            <i class="icon bi-trash-fill"></i>
                                            <span wire:loading.remove
                                                wire:target="delete({{ data_get($form, 'id') }})">{{ __('Remove') }}</span>
                                            <fgx:loader wire:loading
                                                wire:target="delete({{ data_get($form, 'id') }})" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @else
                <fgx:alert soft class="mt-6" :content="__('No item selected')" />
            @endif
        </div>
    </div>



</div>
