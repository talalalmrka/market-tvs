<x-settings-page>
    <x-settings-card
        :title="__('Default')">
        <x-settings-row
            for="default"
            :label="__('Broadcasting Default connection')">
            <fgx:select
                id="default"
                wire:model.live="default"
                :options="$this->connectionOptions" />
        </x-settings-row>
    </x-settings-card>
    <h5 class="mt-6">{{ __('Connections') }}</h5>
    @foreach ($connections as $index => $connection)
        <x-settings-card
            :title="str($index)->title()->value()"
            class="mt-6"
            body-class="space-y-3">
            @foreach (arr_flat($connection) as $key => $value)
                @php
                    $modelKey = "connections.{$index}.{$key}";
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
    @preCard($this->getRules, 'Rules')
</x-settings-page>
