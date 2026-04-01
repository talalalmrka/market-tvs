<div {{ $attributes }}>
    @foreach ($this->items as $item)
        <x-card :title="data_get($item, 'label')" class="mb-6">
            @php
                $value = data_get($item, 'value');
            @endphp
            @if (is_object($value) && method_exists($value, 'toArray'))
                @dump($value->toArray())
            @else
                @dump($value)
            @endif

        </x-card>
    @endforeach

</div>
