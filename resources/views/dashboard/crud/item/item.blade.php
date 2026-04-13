<fgx:card>
    @pre100($this->fields)
    <div class="md:flex md:items-center md:gap-2">
        <div class="relative flex gap-2 items-center md:items-start p-1.5">
            <div class="btn-group sm">
                @foreach ($this->buttons as $button)
                    {!! $button->render() !!}
                @endforeach
            </div>
        </div>
        <div class="md:flex-1 flex flex-wrap items-center gap-2 md:justify-end-safe p-1.5">
            <button wire:click="resetFilters" type="button" title="{{ __('Reset filters') }}"
                class="btn btn-xs pill btn-outline-secondary">
                <i class="icon bi-x-lg"></i>
                <span wire:loading.remove wire:target="resetFilters">{{ __('Reset') }}</span>
                <fgx:loader wire:loading wire:target="resetFilters" />
            </button>
            <div wire:ignore class="dropdown">
                <button type="button" class="btn btn-xs pill btn-outline-secondary dropdown-toggle">
                    <i class="icon bi-filter w-3 h-3"></i>
                </button>
                <div class="dropdown-menu">
                    <div class="text-sm font-medium pb-2 border-b">
                        {{ __('Show columns') }}
                    </div>
                    @foreach ($this->allColumns as $i => $col)
                        <fgx:checkbox
                            id="filters.active_columns.{{ $i }}"
                            wire:model.live="filters.active_columns"
                            label="{{ str($col)->replace(['-', '_'], ' ')->title()->value() }}"
                            value="{{ $col }}"
                            container_class="dropdown-link" />
                    @endforeach
                </div>
            </div>
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
            <thead>
                @include('dashboard.crud.item.head-row')
            </thead>
            <tbody>
                @forelse ($this->items as $index => $item)
                    <tr wire:key="items.{{ $index }}">
                        @foreach ($this->columns as $column)
                            <td wire:key="items.{{ $index }}.{{ $column->name }}" class="{{ $column->class }}">
                                @if ($column->customContent)
                                    {!! call_user_func($column->customContent, $item) !!}
                                @else
                                    {!! data_get($item, $column->name, '') !!}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="{{ $this->columnsCount }}">
                            {{ __('No items found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                @include('dashboard.crud.item.head-row')
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
