<div wire:init="loadEdit({{ request('edit') }})" class="datatable" @itemUpdated($refresh)>
    <div class="relative flex gap-2 items-center md:items-start p-2">
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

        <div class="flex flex-1 gap-2 items-center md:justify-end mt-2 md:mt-0 flex-nowrap overflow-x-auto">
            @if ($this->filtersView)
                {!! $this->filtersView !!}
            @endif
            <div class="inline-flex items-center gap-1">
                <label for="perPage" class="form-label mb-0">{{ __('entries:') }}</label>
                <fgx:select id="perPage" class="xs pill min-w-32" wire:model.live.debounce.300ms="perPage"
                    startIcon="bi-list" :options="per_page_options()" />
            </div>
            <div class="inline-flex items-center gap-1">
                <label class="form-label mb-0" for="search">{{ __('search:') }}</label>
                <fgx:input type="search" id="search" class="xs pill" wire:model.live.debounce.300ms="search"
                    startIcon="bi-search" :placeholder="__('Search')" />
            </div>
        </div>
    </div>
    <div class="table-container">
        <table class="table table-striped table-divide table-rounded xs {{ $this->tableClass }}">
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
            </tfoot>
        </table>
    </div>
    <div class="p-2.5">
        {!! $this->links() !!}
    </div>
</div>
