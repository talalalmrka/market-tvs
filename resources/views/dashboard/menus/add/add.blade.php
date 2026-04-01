<div {{ $attributes->merge([
    'class' => 'card overflow-visible',
]) }}>
    <div class="card-header">
        <div class="card-title text-primary flex items-center gap-2">
            <i class="icon fg-plus"></i>
            <span>{{ __('Add items') }}</span>
            <span class="badge badge-indigo pill flex items-center gap-0.5 parent-badge">
                <i class="icon bi-arrow-90deg-up"></i>
                <span class="parent-span">{{ $this->cardTitle }}</span>
            </span>
        </div>
    </div>
    <div class="card-body">
        <fgx:status class="alert-soft xs mt-2" />
        <div class="max-h-screen overflow-auto">
            <div class="accordion" x-cloak x-data="FgAccordion">
                @foreach ($this->models as $model)
                    @php
                        $modelId = data_get($model, 'id');
                        $modelTitle = data_get($model, 'title');
                        $modelIcon = data_get($model, 'icon');
                    @endphp
                    <div x-bind="item('{{ $modelId }}')">
                        <div x-bind="itemHead('{{ $modelId }}')">
                            <i class="icon {{ $modelIcon }}"></i>
                            <span class="flex-1">{{ $modelTitle }}</span>
                        </div>
                        <div x-bind="itemBody('{{ $modelId }}')">
                            <div class="form-control-container mb-2">
                                <span class="start-icon">
                                    <i class="icon bi-search"></i>
                                </span>
                                <input
                                    type="search"
                                    wire:key="search-{{ $modelId }}"
                                    wire:model.live="search.{{ $modelId }}"
                                    placeholder="{{ __('Search') }}"
                                    class="form-control xs pill mb-1 has-start-icon">
                            </div>
                            <form wire:submit="addItems('{{ $modelId }}')">
                                <div class="max-h-44 overflow-auto">
                                    @forelse ($this->modelItems($modelId) as $m)
                                        <div wire:key="{{ $modelId }}-{{ $m->id }}"
                                            class="form-check flex items-center gap-2 mb-1">
                                            <input type="checkbox"
                                                id="{{ $modelId }}-{{ $m->id }}-checkbox"
                                                value="{{ $m->id }}"
                                                wire:model.live="selected.{{ $modelId }}">
                                            <label for="{{ $modelId }}-{{ $m->id }}-checkbox"
                                                class="form-label mb-0">
                                                {{ $m->name }}
                                            </label>
                                        </div>

                                    @empty
                                        <fgx:alert soft class="xs" :content="__('No items')" />
                                    @endforelse
                                </div>
                                <div class="divider my-1"></div>
                                <div class="flex items-center gap-2 justify-between">
                                    <div class="form-check flex items-center gap-2">
                                        <input type="checkbox" wire:model.live="selectAll.{{ $modelId }}"
                                            id="check-all-pages">
                                        <label for="check-all-{{ $modelId }}" class="form-label mb-0">
                                            {{ __('Select All') }}
                                        </label>
                                    </div>
                                    <button type="submit" class="btn xs btn-primary w-auto text-nowrap"
                                        @if (empty(data_get($selected, $modelId))) disabled @endif>
                                        <span>{{ __('Add to menu') }}</span>
                                    </button>
                                </div>
                                <fgx:status soft size="xs" class="mt-2" id="add_{{ $modelId }}" />
                            </form>
                        </div>
                    </div>
                @endforeach
                <div x-bind="item('custom')">
                    <div x-bind="itemHead('custom')">
                        <i class="icon bi-link"></i>
                        <span class="flex-1">{{ __('Custom link') }}</span>
                    </div>
                    <div x-bind="itemBody('custom')">
                        <form wire:submit="addCustom('{{ $modelId }}')">
                            <div class="grid grid-cols-1 gap-3">
                                <div class="col">
                                    <fgx:input id="custom.name" wire:model.live="custom.name" class="sm"
                                        :label="__('Name')" />
                                </div>
                                <div class="col">
                                    <fgx:icon-picker id="custom.icon" wire:model.live="custom.icon" size="sm"
                                        :label="__('Icon')" :value="data_get($custom, 'icon')" />
                                </div>
                                <div class="col">
                                    <fgx:input id="custom.url" wire:model.live="custom.url" class="sm"
                                        :label="__('Url')" />
                                </div>
                            </div>
                            <div class="divider my-1"></div>
                            <div class="flex items-center gap-2 justify-between">
                                <button type="submit" class="btn xs btn-primary w-auto text-nowrap">
                                    <span>{{ __('Add to menu') }}</span>
                                    <fgx:loader wire:loading wire:target="addCustom" />
                                </button>
                                <fgx:status soft size="xs" class="mt-2" id="add_custom" />
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
