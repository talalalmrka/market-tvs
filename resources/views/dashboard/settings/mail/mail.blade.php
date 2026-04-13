<x-settings-page>
    <x-settings-card
        body-class="space-y-4">
        <x-settings-row
            for="default"
            :label="__('Default mailer')">
            <fgx:radio
                id="default"
                wire:model.live="default"
                :options="mailer_options()" />
        </x-settings-row>
        <x-settings-row
            for="from.address"
            :label="__('From address')">
            <fgx:input
                id="from.address"
                wire:model.live="from.address"
                start-icon="bi-envelope" />
        </x-settings-row>
        <x-settings-row
            for="from.name"
            :label="__('From name')">
            <fgx:input
                id="from.name"
                wire:model.live="from.name"
                start-icon="bi-person" />
        </x-settings-row>
    </x-settings-card>
    <h3 class="mt-6">{{ __('Mailers') }}</h3>
    @foreach ($mailers as $key => $mailer)
        @php
            $show = $key === $default;
        @endphp
        <x-settings-card
            wire:cloak
            wire:show="show.{{ $key }}"
            :title="$this->cardTitle($key)"
            class="mb-4"
            body-class="space-y-4">
            @foreach ($mailer as $name => $value)
                @php
                    $itemKey = "mailers.{$key}.{$name}";
                    $label = str("{$key}.{$name}")
                        ->replace(['.', '-', '_'], ' ')
                        ->title()
                        ->value();
                    // $label = str_title(str_replace(['.', '-', '_'], ' ', "{$key}.{$name}"));
                @endphp
                <x-settings-row
                    :for="$itemKey"
                    :label="$label">
                    <fgx:input
                        id="{{ $itemKey }}"
                        wire:model.live="{{ $itemKey }}" />
                </x-settings-row>
            @endforeach
        </x-settings-card>
    @endforeach

    {{-- @pre($this->all(), 'pre-100') --}}
</x-settings-page>
