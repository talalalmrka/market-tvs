<x-settings-page>
    <x-settings-card body-class="flex items-center gap-2">
        <fgx:input type="search" wire:model.live="search" id="search" :placeholder="__('Search...')"
            start-icon="bi-search" class="xs pill w-60" />
    </x-settings-card>
    <x-settings-card class="mt-4" body-class="space-y-3">
        @forelse ($this->fields as $key => $value)
            @php
                $model = "{$prefix}.{$key}";
                $label = str($key)
                    ->replace("{$path}.", '')
                    ->replace(['.', '-', '_'], ' ')
                    ->title()
                    ->value();
                $type = get_field_type($key, $value);
            @endphp
            <x-settings-row
                :for="$model"
                :label="$label">
                <span class="text-xs text-muted">{{ $key }}</span>
                @switch($type)
                    @case('text')
                        <fgx:input
                            :id="$model"
                            wire:model.live="{{ $model }}" />
                    @break

                    @case('number')
                        <fgx:input
                            type="number"
                            :id="$model"
                            wire:model.live="{{ $model }}" />
                    @break

                    @case('switch')
                        <fgx:switch
                            :id="$model"
                            wire:model.live="{{ $model }}"
                            :checked="boolval($value)" />
                    @break

                    @case('select')
                        <fgx:select
                            :id="$model"
                            wire:model.live="{{ $model }}"
                            :options="select_options($key)" />
                    @break

                    @case('switch_group')
                        <fgx:switch-group
                            :id="$model"
                            wire:model.live="{{ $model }}"
                            :options="arr_options(config($key))" />
                    @break
                @endswitch
                @if (config_dirty($key, $value))
                    <button wire:click="resetField('{{ $key }}')" type="button" class="link text-xs">
                        <span wire:loading.remove
                            wire:target="resetField('{{ $key }}')">{{ __('Reset') }}</span>
                        <fgx:loader wire:loading wire:target="resetField('{{ $key }}')" />
                    </button>
                @endif
                {{-- <span class="text-xs text-muted">{{ config_value_safe(config($key)) }}</span> --}}
            </x-settings-row>
            @empty
                <div class="alert alert-soft alert-info sm">
                    No items
                </div>
            @endforelse
        </x-settings-card>
        @preCard($errors, 'Errors')
        @preCard($this->fields, 'Fields')
        @preCard($form, 'Form')
        @preCard($this->settings->toArray(), 'Settings')
        @preCard($this->getRules, 'Rules')
        </x-settings-card>
