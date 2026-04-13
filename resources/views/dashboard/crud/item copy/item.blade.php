<fgx:card>
    <div class="md:flex md:items-center md:gap-2">
        <div class="relative flex gap-2 items-center md:items-start p-1.5">

        </div>
        <div class="md:flex-1 flex flex-wrap items-center gap-2 md:justify-end-safe p-1.5">
            <div class="inline-flex items-center gap-1">
                <fgx:select
                    id="filters.per_page"
                    class="xs pill has-end-icon"
                    wire:model.live.debounce.300ms="filters.per_page"
                    startIcon="bi-list"
                    :options="per_page_options()" />
            </div>
            <div class="inline-flex items-center gap-1">
                <fgx:input
                    type="filters.search"
                    id="filters.search"
                    class="xs pill w-35"
                    wire:model.live.debounce.300ms="filters.search"
                    startIcon="bi-search"
                    :placeholder="__('Search')" />
            </div>
        </div>
    </div>
    <div class="table-container">
        <table
            class="table table-striped table-divide table-rounded table-border table-auto xs">
            <thead class="">
                <tr>
                    <th>
                        <fgx:checkbox
                            id="filters.select_all"
                            wire:model.live="filters.select_all" />
                    </th>
                    @foreach ($this->columns() as $column)
                        <th>
                            @if (data_get($column, 'sortable'))
                                <div wire:click="sortBy('{{ $column['name'] }}')"
                                    class="w-full flex justify-between flex-space-2 cursor-pointer">
                                    <div class="grow">{{ $column['label'] }}</div>
                                    <div class="flex flex-col text-xxs">
                                        <i
                                            class="icon bi-chevron-up {{ $filters['sort_field'] === $column['name'] && $filters['sort_direction'] === 'asc' ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500' }}"></i>
                                        <i
                                            class="icon bi-chevron-down {{ $filters['sort_field'] === $column['name'] && $filters['sort_direction'] === 'desc' ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500' }}"></i>
                                    </div>

                                </div>
                            @else
                                {{ $column['label'] }}
                            @endif
                        </th>
                    @endforeach
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="">
                @if ($this->items()->isNotEmpty())
                    @foreach ($this->items() as $item)
                        <tr
                            wire:key="row-{{ $item->getKey() }}">
                            <td>
                                <fgx:checkbox id="filters.selected" wire:model.live="filters.selected"
                                    value="{{ $item->getKey() }}" />
                            </td>
                            @foreach ($this->columns() as $column)
                                <td>
                                    {{ data_get($item, $column['name']) }}
                                </td>
                            @endforeach
                            <td>
                                <div class="flex items-center justify-center-safe gap-2 md:gap-3">
                                    <button wire:click="delete({{ $item->getKey() }})" type="button"
                                        title="{{ __('Delete') }}">
                                        <i wire:loading.remove wire:target="delete({{ $item->getKey() }})"
                                            class="icon bi-trash-fill"></i>
                                        <fgx:loader wire:loading wire:target="delete({{ $item->getKey() }})" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="{{ $this->cellClass }} text-center" colspan="{{ $this->columnsCount }}">
                            {{ __('No items found.') }}
                        </td>
                    </tr>
                @endif
            </tbody>
            <tfoot class="">
                <tr>
                    <th>
                        <fgx:checkbox
                            id="filters.select_all"
                            wire:model.live="filters.select_all" />
                    </th>
                    @foreach ($this->columns() as $column)
                        <th>
                            @if (data_get($column, 'sortable'))
                                <div wire:click="sortBy('{{ $column['name'] }}')"
                                    class="w-full flex justify-between flex-space-2 cursor-pointer">
                                    <div class="grow">{{ $column['label'] }}</div>
                                    <div class="flex flex-col text-xxs">
                                        <i
                                            class="icon bi-chevron-up {{ $filters['sort_field'] === $column['name'] && $filters['sort_direction'] === 'asc' ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500' }}"></i>
                                        <i
                                            class="icon bi-chevron-down {{ $filters['sort_field'] === $column['name'] && $filters['sort_direction'] === 'desc' ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500' }}"></i>
                                    </div>

                                </div>
                            @else
                                {{ $column['label'] }}
                            @endif
                        </th>
                    @endforeach
                    <th>{{ __('Actions') }}</th>
                </tr>
                <tr>
                    <td colspan="{{ $this->columnsCount }}">
                        <div class="flex items-center justify-between">
                            <span>
                                {{ __(':count items.', ['count' => $this->count]) }}
                            </span>
                            <span>
                                {{ __(':count selected.', ['count' => $this->selectedCount]) }}
                            </span>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="p-2.5">
        {!! $this->items()?->links() !!}
    </div>
</fgx:card>
