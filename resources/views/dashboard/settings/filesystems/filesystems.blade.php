<x-settings-page>
    <x-settings-card
        :title="__('Default')">
        <x-settings-row
            for="default"
            :label="__('Default Filesystem Disk')">
            <fgx:select
                id="default"
                wire:model.live="default"
                :options="$this->diskOptions" />
        </x-settings-row>
    </x-settings-card>
    <x-settings-card
        :title="__('Links')"
        class="mt-6"
        body-class="space-y-3">
        @foreach ($links as $index => $link)
            <div class="border rounded-2xl p-2 space-y-3">
                <x-settings-row
                    for="links.{{ $index }}.target"
                    :label="__('Target')">
                    <fgx:input
                        id="links.{{ $index }}.target"
                        wire:model.live="links.{{ $index }}.target" />
                </x-settings-row>
                <x-settings-row
                    for="links.{{ $index }}.path"
                    :label="__('Path')">
                    <fgx:input
                        id="links.{{ $index }}.path"
                        wire:model.live="links.{{ $index }}.path" />
                </x-settings-row>
                <button
                    type="button"
                    wire:click="removeLink({{ $index }})"
                    class="btn btn-outline-red btn-xs mt-3">
                    <i class="icon bi-trash-fill"></i>
                    <span wire:loading.remove wire:target="removeLink({{ $index }})">{{ __('Remove') }}</span>
                    <i wire:loading wire:target="removeLink({{ $index }})" class="icon fg-loader-dots-move"></i>
                </button>
            </div>
        @endforeach
        <button
            type="button"
            wire:click="addLink"
            class="btn btn-outline-primary btn-sm mt-3">
            <i class="icon fg-plus"></i>
            <span wire:loading.remove wire:target="addLink">{{ __('Add link') }}</span>
            <i wire:loading wire:target="addLink" class="icon fg-loader-dots-move"></i>
        </button>
    </x-settings-card>
    <x-settings-card
        :title="__('Symlinks')"
        class="mt-6"
        body-class="space-y-3">
        @foreach ($this->symlinks as $sym)
            <div class="border shdow-xs mb-3 rounded-2xl p-2">
                @php
                    $path = data_get($sym, 'path');
                    $target = data_get($sym, 'target');
                    $path_exists = data_get($sym, 'path_exists');
                    $target_exists = data_get($sym, 'target_exists');
                    $linked = data_get($sym, 'linked');
                @endphp
                <div class="mb-2">
                    <label class="form-label">{{ __('Path') }}</label>
                    <input readonly class="form-control" value="{{ $path }}">
                </div>
                <div>
                    <label class="form-label">{{ __('Target') }}</label>
                    <input readonly class="form-control" value="{{ $target }}">
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="badge pill {{ $path_exists ? 'badge-green' : 'badge-red' }}">
                        {{ __('Path') }}
                    </span>
                    <span class="badge pill {{ $target_exists ? 'badge-green' : 'badge-red' }}">
                        {{ __('Target') }}
                    </span>
                    <span class="badge pill {{ $linked ? 'badge-green' : 'badge-red' }}">
                        {{ __('Linked') }}
                    </span>
                </div>
            </div>
        @endforeach
        <div class="flex items-center justify-between">
            <button
                type="button"
                wire:click="linkStorage"
                class="btn btn-outline-primary btn-sm mt-3">
                <i class="icon bi-link-45deg"></i>
                <span wire:loading.remove wire:target="linkStorage">{{ __('Storage link') }}</span>
                <i wire:loading wire:target="linkStorage" class="icon fg-loader-dots-move"></i>
            </button>
            <button
                type="button"
                wire:click="unlinkStorage"
                class="btn btn-outline-secondary btn-sm mt-3">
                <i class="icon bi-x"></i>
                <span wire:loading.remove wire:target="unlinkStorage">{{ __('Storage unlink') }}</span>
                <i wire:loading wire:target="unlinkStorage" class="icon fg-loader-dots-move"></i>
            </button>
        </div>
        <pre wire:stream="output" class="pre-100"></pre>
    </x-settings-card>
    <h5 class="mt-6">{{ __('Disks') }}</h5>
    @foreach ($disks as $index => $disk)
        <x-settings-card
            :title="str($index)->title()->value()"
            class="mt-6"
            body-class="space-y-3">
            @foreach (arr_flat($disk) as $key => $value)
                @php
                    $modelKey = "disks.{$index}.{$key}";
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
