<x-settings-page>
    <x-settings-card
        :title="__('Default')">
        <x-settings-row
            for="default"
            :label="__('Database Default connection')">
            <fgx:select
                id="default"
                wire:model.live="default"
                :options="$this->connectionOptions" />
        </x-settings-row>
    </x-settings-card>
    <x-settings-card
        :title="__('Migrations')"
        class="mt-6"
        body-class="space-y-3">
        <x-settings-row
            for="migrations.table"
            :label="__('Table')">
            <fgx:input
                id="migrations.table"
                wire:model.live="migrations.table" />
        </x-settings-row>
        <x-settings-row
            for="migrations.update_date_on_publish"
            :label="__('Update date on publish')">
            <fgx:switch
                id="migrations.update_date_on_publish"
                wire:model.live="migrations.update_date_on_publish"
                :checked="boolval(data_get($migrations, 'update_date_on_publish'))" />
        </x-settings-row>
    </x-settings-card>
    <x-settings-card
        :title="__('Redis')"
        class="mt-6"
        body-class="space-y-3">
        @foreach (arr_flat($redis) as $key => $value)
            @php
                $modelKey = "redis.{$key}";
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
