<div class="datatable" @itemUpdated($refresh)>
    <div class="md:flex md:items-center md:gap-2">
        <div class="relative flex gap-2 items-center md:items-start p-1.5">
            @if ($this->hasButtons)
                <div class="btn-group sm">
                    @foreach ($this->buttons as $button)
                        {!! $button->render() !!}
                    @endforeach
                    @if ($this->buttonsView)
                        {!! $this->buttonsView !!}
                    @endif
                </div>
            @endif
        </div>
        <div class="md:flex-1 flex flex-wrap items-center gap-2 md:justify-end-safe p-1.5">
            @if ($this->filtersView)
                {!! $this->filtersView !!}
            @endif
            <div class="inline-flex items-center gap-1">
                <fgx:select id="perPage" class="xs pill has-end-icon" wire:model.live.debounce.300ms="perPage"
                    startIcon="bi-list" :options="per_page_options()" />
            </div>
            <div class="inline-flex items-center gap-1">
                <fgx:input type="search" id="search" class="xs pill w-35" wire:model.live.debounce.300ms="search"
                    startIcon="bi-search" :placeholder="__('Search')" />
            </div>
        </div>
    </div>

    <div class="table-container">
        <table
            class="table table-striped table-divide table-rounded table-border table-auto xs {{ $this->tableClass }}">
            <thead class="{{ $this->headClass }}">
                @include('livewire.components.datatable.head-row')
            </thead>
            <tbody class="{{ $this->bodyClass }}">
                @if ($this->items()->isNotEmpty())
                    @foreach ($this->items() as $item)
                        <tr class="{{ $this->rowClass }}"
                            wire:key="row-{{ $item->getKey() }}">
                            @foreach ($this->cols() as $col)
                                <td class="{{ $this->cellClass }}{{ !empty($col->class) ? ' ' . $col->class : '' }}">
                                    @if ($col->customContent)
                                        {!! call_user_func($col->customContent, $item) !!}
                                    @else
                                        {!! data_get($item, $col->name, '') !!}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @else
                    <tr class="{{ $this->rowClass }}">
                        <td class="{{ $this->cellClass }} text-center" colspan="{{ sizeof($this->cols()) }}">
                            {{ __('No items found.') }}
                        </td>
                    </tr>
                @endif
            </tbody>
            <tfoot class="{{ $this->footClass }}">
                @include('livewire.components.datatable.head-row')
                <tr>
                    <td colspan="{{ sizeof($this->cols()) }}">
                        <div class="flex items-center justify-between">
                            <span>
                                {{ __(':count items.', ['count' => $count]) }}
                            </span>
                            <span>
                                {{ __(':count selected.', ['count' => sizeof($selected)]) }}
                            </span>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="p-2.5">
        {!! $this->links() !!}
    </div>
</div>
