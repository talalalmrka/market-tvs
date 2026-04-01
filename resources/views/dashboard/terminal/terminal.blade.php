<div x-data="{
    output: $wire.entangle('output'),
    setCommand(cmd) {
        this.$wire.command = cmd;
        this.scrollToBottom();
        this.focusInput();
    },
    focusInput() {
        this.$refs.input.focus();
    },
    scrollToBottom() {
        this.$refs.output.scrollTop = this.$refs.output.scrollHeight;
    },
    clear() {
        this.output = '';
        this.$refs.pre.innerHTML = '';
        this.focusInput();
    },
    init() {
        this.$watch('output', (val) => {
            console.log(val);
            this.scrollToBottom();
        });
    }
}">
    <div class="card">
        <div class="card-header text-primary">
            <div class="card-title">{{ __('Available commands') }}</div>
        </div>
        <div class="card-body">
            <div class="w-40 mb-3">
                <fgx:input type="search" id="search" wire:model.live="search" start-icon="bi-search"
                    :placeholder="__('Search')" class="xs pill" />
            </div>

            <table class="table table-border table-striped table-divide table-rounded table-auto xs">
                <thead>
                    <tr>
                        <th>{{ __('Command') }}</th>
                        <th>{{ __('Description') }}</th>
                    </tr>
                </thead>
                <tbody class="max-h-96 overflow-auto">
                    @foreach ($this->commands as $item)
                        @php
                            $cmdName = data_get($item, 'name');
                            $cmdDescription = data_get($item, 'description');
                        @endphp
                        <tr>
                            <td>
                                <button type="button" title="{{ $cmdDescription }}"
                                    x-on:click="setCommand('{{ $cmdName }}')"
                                    class="badge badge-primary pill">
                                    {{ $cmdName }}
                                </button>
                            </td>
                            <td>{{ $cmdDescription }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header text-primary">
            <div class="card-title flex items-center gap-2">
                <div class="flex-1">{{ __('Terminal') }}</div>
                <button x-on:click="clear" type="button" class="btn btn-secondary btn-sm">
                    <i class="icon bi-trash"></i>
                    <span>{{ __('Clear') }}</span>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div wire:ignore id="output" x-ref="output"
                class="p-3 bg-black text-white font-mono text-sm rounded h-84 overflow-auto">
                <pre x-ref="pre" wire:stream="output" class="whitespace-pre-wrap break-words bg-transparent p-2">{{ $output }}</pre>
                <form wire:submit.prevent="submitCommand" class="flex gap-2">
                    <span class="text-sm">> php artisan</span>
                    <input x-ref="input" type="text" wire:model.live="command"
                        placeholder="Enter command (e.g., migrate)"
                        class="flex-1 appearance-none focus:outline-0 border-0 h-auto text-sm"
                        autofocus />
                </form>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        $js('scrollToBottom', () => {
            $nextTick(() => {
                const output = document.querySelector('#output');
                output.scrollTop = output.scrollHeight;
            });

        })
    </script>
@endscript
