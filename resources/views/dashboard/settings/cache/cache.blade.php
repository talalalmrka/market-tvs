<x-settings-page>
    <x-settings-card
        :title="__('Default')"
        body-class="space-y-3">
        <x-settings-row
            for="default"
            :label="__('Cache Default store')">
            <fgx:select
                id="default"
                wire:model.live="default"
                :options="$this->storeOptions" />
        </x-settings-row>
        <x-settings-row
            for="prefix"
            :label="__('Cache prefix')">
            <fgx:input
                id="prefix"
                wire:model.live="default" />
        </x-settings-row>
    </x-settings-card>
    <h5 class="mt-6">{{ __('Stores') }}</h5>
    @foreach ($stores as $index => $store)
        <x-settings-card
            :title="str($index)->title()->value()"
            class="mt-6"
            body-class="space-y-3">
            @foreach (arr_flat($store) as $key => $value)
                @php
                    $modelKey = "stores.{$index}.{$key}";
                    $label = str($key)
                        ->replace(['.', '-', '_'], ' ')
                        ->title()
                        ->value();
                    $type = gettype($value);
                @endphp
                <x-settings-row
                    :for="$modelKey"
                    :label="$label">
                    @if ($type === 'boolean')
                        <fgx:switch
                            id="{{ $modelKey }}"
                            wire:model.live="{{ $modelKey }}"
                            :checked="boolval($value)" />
                    @else
                        <fgx:input
                            type="{{ $type === 'integer' ? 'number' : 'text' }}"
                            id="{{ $modelKey }}"
                            wire:model.live="{{ $modelKey }}" />
                    @endif
                    <span class="text-xs text-muted">{{ $modelKey }}</span>
                </x-settings-row>
            @endforeach
        </x-settings-card>
    @endforeach
</x-settings-page>
