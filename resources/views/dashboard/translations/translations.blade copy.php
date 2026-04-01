<div>
    <div class="card overflow-visible">
        <div class="datatable">
            <div class="relative flex gap-2 items-center md:items-start p-2">
                <div x-cloak x-data="{ open: false }" x-on:click.away="open = false" class="relative">
                    <button x-on:click="open = !open" type="button" class="btn btn-xs btn-primary !space-x-0.5 px-2">
                        @icon('bi-plus-lg')
                        <span>{{ __('Create') }}</span>
                    </button>
                    <div x-collapse x-show="open" class="dropdown-menu show">
                        <div class="grid grid-cols-1 gap-3">
                            <div class="col">
                                <fgx:input id="newTranslation" wire:model.live="newTranslation"
                                    :placeholder="__('Locale like en,ar,ru,fr')" class="sm" />
                            </div>
                            <div class="col">
                                <button wire:click="create" type="button" class="btn btn-sm btn-primary">
                                    <i class="icon bi-floppy"></i>
                                    <span wire:loading.remove wire:target="create">{{ __('Create') }}</span>
                                    <fgx:loader wire:loading wire:target="create" />
                                </button>
                                <fgx:status id="create" class="alert-soft xs mt-2" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table class="table table-striped table-divide table-rounded xs">
                    <thead>
                        <tr>
                            <th>{{ __('Locale') }}</th>
                            <th>{{ __('All words') }}</th>
                            <th>{{ __('Complete') }}</th>
                            <th>{{ __('Pending') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($this->translations && $this->translations->isNotEmpty())
                            @foreach ($this->translations as $item)
                                <tr wire:key="{{ $item->locale }}">
                                    <td>{{ $item->locale }}</td>
                                    <td>{{ $item->wordsCount() }}</td>
                                    <td>{{ $item->wordsCount('completed') }}</td>
                                    <td>{{ $item->wordsCount('pending') }}</td>
                                    <td>
                                        <div class="flex items-center gap-2 md:gap-3">
                                            <button type="button" wire:click="edit('{{ $item->locale }}')"
                                                aria-label="{{ __('Edit') }}" title="{{ __('Edit') }}">
                                                <i class="icon bi-pencil-square w-4 h-4" wire:loading.remove
                                                    wire:target="edit('{{ $item->locale }}')"></i>
                                                <i wire:loading wire:target="edit('{{ $item->locale }}')"
                                                    class="icon fg-loader-dots-move"></i>
                                            </button>
                                            <button type="button" wire:click="sync('{{ $item->locale }}')"
                                                aria-label="{{ __('Sync') }}" title="{{ __('Sync') }}">
                                                <i class="icon bi-arrow-repeat w-4 h-4" wire:loading.remove
                                                    wire:target="sync('{{ $item->locale }}')"></i>
                                                <i wire:loading wire:target="sync('{{ $item->locale }}')"
                                                    class="icon fg-loader-dots-move"></i>
                                            </button>
                                            <button type="button" wire:click="delete('{{ $item->locale }}')"
                                                aria-label="{{ __('Delete') }}" title="{{ __('Delete') }}">
                                                <i class="icon bi-trash w-4 h-4" wire:loading.remove
                                                    wire:target="delete('{{ $item->locale }}')"></i>
                                                <i wire:loading wire:target="delete('{{ $item->locale }}')"
                                                    class="icon fg-loader-dots-move"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">{{ __('No translations found') }}</td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{{ __('Locale') }}</th>
                            <th>{{ __('All words') }}</th>
                            <th>{{ __('Complete') }}</th>
                            <th>{{ __('Pending') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @if (!empty($locale))
        <form wire:submit="save">
            <div class="card mt-6">
                <div class="card-header text-primary flex items-center gap-2 justify-between">
                    <div class="card-title flex-1">
                        {{ __('Edit (:locale)', ['locale' => $locale]) }}
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" class="btn btn-xs btn-primary flex items-center gap-0.5! px-2">
                            @icon('bi-plus-lg')
                            <span>{{ __('Add') }}</span>
                        </button>
                        <button wire:click="sync('{{ $locale }}')" type="button"
                            class="btn btn-xs btn-green !space-x-0.5 px-2">
                            <i class="icon bi-arrow-repeat w-4 h-4"></i>
                            <span wire:loading.remove
                                wire:target="sync('{{ $locale }}')">{{ __('Sync') }}</span>
                            <i wire:loading wire:target="sync('{{ $locale }}')"
                                class="icon fg-loader-dots-move"></i>
                        </button>
                        <a wire:navigate href="{{ route('dashboard.translations') }}" title="{{ __('Close') }}"
                            class="btn btn-xs btn-secondary !space-x-0.5 px-2">
                            @icon('bi-x-lg')
                            <span>{{ __('Close') }}</span>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 gap-3">
                        @foreach ($keysMap as $safeKey => $origKey)
                            <div class="col">
                                <fgx:textarea id="words_{{ $safeKey }}"
                                    wire:key="words_{{ $safeKey }}"
                                    wire:model.live="safeWords.{{ $safeKey }}"
                                    :label="$origKey"
                                    class="{{ empty($safeWords[$safeKey] ?? '') ? 'border-orange-300 focus:ring-orange-300' : '' }}"
                                    rows="1"
                                    :info="$safeWords[$safeKey] ?? ''" />
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        {{ $this->wordsPaginator?->links() }}
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 bg-white dark:bg-gray-700 mt-3 px-3 py-2 border rounded-lg shadow">
                <button type="submit" class="btn btn-primary">
                    <i class="icon bi-floppy"></i>
                    <span wire:loading.remove wire:target="save">{{ __('Save') }}</span>
                    <fgx:loader wire:loading wire:target="save" />
                </button>
                <fgx:status id="save" class="alert-soft xs mt-2" />
            </div>
        </form>
    @endif
    <x-center-loader wire:loading />
</div>
