<tr>
    @foreach ($this->columns() as $column)
        <th class="{{ $column->headClass }}">
            @if ($column->sortable)
                <div wire:click="sortBy('{{ $column->name }}')"
                    class="w-full flex items-center gap-1.5 cursor-pointer select-none">
                    <div class="flex-1">{{ $column->getLabel() }}</div>
                    <div class="flex flex-col text-xxs">
                        <i
                            class="icon bi-chevron-up {{ $filters['sort_field'] === $column->name && $filters['sort_direction'] === 'asc' ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500' }}"></i>
                        <i
                            class="icon bi-chevron-down {{ $filters['sort_field'] === $column->name && $filters['sort_direction'] === 'desc' ? 'text-gray-900 dark:text-gray-100' : 'text-gray-400 dark:text-gray-500' }}"></i>
                    </div>

                </div>
            @else
                {{ $column->getLabel() }}
            @endif
        </th>
    @endforeach
</tr>
