<div>
    <div class="card overflow-visible">
        <div class="md:flex md:items-center md:gap-2 p-2">
            <div class="relative flex gap-2 items-center md:items-start">
                <div x-cloak x-data="{ open: false }" x-on:click.away="open = false" class="relative">
                    <button x-on:click="open = !open" type="button"
                        class="btn btn-xs btn-primary !space-x-0.5 px-2">
                        @icon('bi-plus-lg')
                        <span>{{ __('Create') }}</span>
                    </button>

                    <div x-collapse x-show="open" class="dropdown-menu show">
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <fgx:input id="newTranslation"
                                    wire:model.live="newTranslation"
                                    :placeholder="__('Locale like en,ar,ru,fr')"
                                    class="sm" />
                            </div>

                            <div>
                                <button wire:click="create" type="button"
                                    class="btn btn-sm btn-primary">
                                    <i class="icon bi-floppy"></i>
                                    <span wire:loading.remove wire:target="create">
                                        {{ __('Create') }}
                                    </span>
                                    <fgx:loader wire:loading wire:target="create" />
                                </button>

                                <fgx:status id="create"
                                    class="alert-soft xs mt-2" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-1 flex items-center justify-end-safe gap-2">
                <div class="inline-flex items-center gap-1">
                    <label for="translationsPerPage" class="form-label mb-0">{{ __('entries:') }}</label>
                    <div class="form-control-container">
                        <span class="start-icon">
                            <i class="icon bi-list"></i> </span>
                        <select id="translationsPerPage" class="form-select xs pill min-w-32 has-start-icon"
                            wire:model.live.debounce.300ms="translationsPerPage">
                            <option value="1">1</option>
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="25">25</option>
                            <option value="30">30</option>
                            <option value="35">35</option>
                            <option value="40">40</option>
                            <option value="45">45</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                </div>
                <div class="inline-flex items-center gap-1">
                    <label class="form-label mb-0" for="search">{{ __('search:') }}</label>
                    <div class="form-control-container">
                        <span class="start-icon">
                            <i class="icon bi-search"></i> </span>
                        <input id="translationsSearch" type="search" placeholder="{{ __('Search') }}"
                            class="form-control has-start-icon xs pill"
                            wire:model.live.debounce.300ms="translationsSearch">

                    </div>

                </div>
            </div>
        </div>
        <div class="datatable">
            {{-- Table --}}
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
                        @forelse ($this->translations as $item)
                            <tr wire:key="locale-{{ $item->locale }}">
                                <td>{{ $item->locale }}</td>
                                <td>{{ $item->wordsCount() }}</td>
                                <td>{{ $item->wordsCount('completed') }}</td>
                                <td>{{ $item->wordsCount('pending') }}</td>
                                <td>
                                    <div class="flex items-center gap-2 md:gap-3">
                                        <button type="button"
                                            wire:click="edit('{{ $item->locale }}')"
                                            title="{{ __('Edit') }}">
                                            <i class="icon bi-pencil-square w-4 h-4"
                                                wire:loading.remove
                                                wire:target="edit('{{ $item->locale }}')"></i>
                                            <i wire:loading
                                                wire:target="edit('{{ $item->locale }}')"
                                                class="icon fg-loader-dots-move"></i>
                                        </button>

                                        <button type="button"
                                            wire:click="sync('{{ $item->locale }}')"
                                            title="{{ __('Sync') }}">
                                            <i class="icon bi-arrow-repeat w-4 h-4"
                                                wire:loading.remove
                                                wire:target="sync('{{ $item->locale }}')"></i>
                                            <i wire:loading
                                                wire:target="sync('{{ $item->locale }}')"
                                                class="icon fg-loader-dots-move"></i>
                                        </button>

                                        <button type="button"
                                            wire:click="delete('{{ $item->locale }}')"
                                            title="{{ __('Delete') }}">
                                            <i class="icon bi-trash w-4 h-4"
                                                wire:loading.remove
                                                wire:target="delete('{{ $item->locale }}')"></i>
                                            <i wire:loading
                                                wire:target="delete('{{ $item->locale }}')"
                                                class="icon fg-loader-dots-move"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    {{ __('No translations found') }}
                                </td>
                            </tr>
                        @endforelse
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

            {{-- Pagination --}}
            @if (method_exists($this->translations, 'links'))
                <div class="p-3 border-t">
                    {{ $this->translations->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- Edit Section --}}
    @if (!empty($locale))
        <form wire:submit="save">
            <div class="card mt-6">
                <div class="card-header text-primary flex items-center gap-2 justify-between">
                    <div class="card-title flex-1">
                        {{ __('Edit (:locale)', ['locale' => $locale]) }}
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button"
                            class="btn btn-xs btn-primary flex items-center gap-0.5 px-2">
                            @icon('bi-plus-lg')
                            <span>{{ __('Add') }}</span>
                        </button>

                        <button wire:click="sync('{{ $locale }}')"
                            type="button"
                            class="btn btn-xs btn-green !space-x-0.5 px-2">
                            <i class="icon bi-arrow-repeat w-4 h-4"></i>
                            <span wire:loading.remove
                                wire:target="sync('{{ $locale }}')">
                                {{ __('Sync') }}
                            </span>
                            <i wire:loading
                                wire:target="sync('{{ $locale }}')"
                                class="icon fg-loader-dots-move"></i>
                        </button>

                        <a wire:navigate
                            href="{{ route('dashboard.translations') }}"
                            class="btn btn-xs btn-secondary !space-x-0.5 px-2">
                            @icon('bi-x-lg')
                            <span>{{ __('Close') }}</span>
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="md:flex md:items-center md:gap-2 p-2">
                        <div class="">
                            start
                        </div>
                        {{-- <div class="relative flex gap-2 items-center md:items-start">
                            <div x-cloak x-data="{ open: false }" x-on:click.away="open = false" class="relative">
                                <button x-on:click="open = !open" type="button"
                                    class="btn btn-xs btn-primary !space-x-0.5 px-2">
                                    @icon('bi-plus-lg')
                                    <span>{{ __('Create') }}</span>
                                </button>

                                <div x-collapse x-show="open" class="dropdown-menu show">
                                    <div class="grid grid-cols-1 gap-3">
                                        <div>
                                            <fgx:input id="newTranslation"
                                                wire:model.live="newTranslation"
                                                :placeholder="__('Locale like en,ar,ru,fr')"
                                                class="sm" />
                                        </div>

                                        <div>
                                            <button wire:click="create" type="button"
                                                class="btn btn-sm btn-primary">
                                                <i class="icon bi-floppy"></i>
                                                <span wire:loading.remove wire:target="create">
                                                    {{ __('Create') }}
                                                </span>
                                                <fgx:loader wire:loading wire:target="create" />
                                            </button>

                                            <fgx:status id="create"
                                                class="alert-soft xs mt-2" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="flex-1 flex items-center justify-end-safe gap-2">
                            <div class="inline-flex items-center gap-1">
                                <label for="wordsPerPage" class="form-label mb-0">{{ __('entries:') }}</label>
                                <div class="form-control-container">
                                    <span class="start-icon">
                                        <i class="icon bi-list"></i> </span>
                                    <select id="translationsPerPage"
                                        class="form-select xs pill min-w-32 has-start-icon"
                                        wire:model.live.debounce.300ms="wordsPerPage">
                                        <option value="1">1</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="15">15</option>
                                        <option value="20">20</option>
                                        <option value="25">25</option>
                                        <option value="30">30</option>
                                        <option value="35">35</option>
                                        <option value="40">40</option>
                                        <option value="45">45</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="150">150</option>
                                        <option value="200">200</option>
                                        <option value="300">300</option>
                                        <option value="400">400</option>
                                        <option value="500">500</option>
                                    </select>
                                </div>

                            </div>
                            <div class="inline-flex items-center gap-1">
                                <label class="form-label mb-0" for="search">{{ __('search:') }}</label>
                                <div class="form-control-container">
                                    <span class="start-icon">
                                        <i class="icon bi-search"></i> </span>
                                    <input id="wordsSearch" type="search" placeholder="{{ __('Search') }}"
                                        class="form-control has-start-icon xs pill"
                                        wire:model.live.debounce.300ms="wordsSearch">

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                        @foreach ($form as $index => $item)
                            <div class="col">
                                <fgx:textarea
                                    id="form.{{ $index }}.value"
                                    wire:model.live="form.{{ $index }}.value"
                                    label="{{ data_get($item, 'label') }}"
                                    rows="1"
                                    :info="data_get($item, 'value', '')"
                                    class="{{ empty(data_get($item, 'value', '')) ? 'border-orange-300 focus:ring-orange-300' : '' }}" />
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            {{-- Sticky Save Bar --}}
            <div class="sticky bottom-0 bg-white dark:bg-gray-700 mt-3 px-3 py-2 border rounded-lg shadow">
                <button type="submit" class="btn btn-primary">
                    <i class="icon bi-floppy"></i>
                    <span wire:loading.remove wire:target="save">
                        {{ __('Save') }}
                    </span>
                    <fgx:loader wire:loading wire:target="save" />
                </button>

                <fgx:status id="save"
                    class="alert-soft xs mt-2" />
            </div>
        </form>
    @endif

    <x-center-loader wire:loading />
</div>
