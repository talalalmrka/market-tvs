@php
    $items = dashboard_sidebar_items();
@endphp
<x-layouts::app.header title="Sidebar">
    <div class="grid grid-cols-2 gap-4">
        <div class="col">
            @pre($items->toArray(), 'pre-100')
        </div>
        <div class="col">
            <nav class="nav nav-vertical sidebar-nav">
                @foreach ($items as $item)
                    {!! $item->render() !!}
                @endforeach
            </nav>
        </div>
    </div>
</x-layouts::app.header>
