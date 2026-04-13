@props(['item', 'actions'])
<div class="flex items-center gap-2 md:gap-3 justify-center-safe">
    @foreach ($actions as $action)
        @include(
            'livewire.components.datatable.action',
            array_merge($action->toArray(), ['item' => $item]))
    @endforeach
</div>
