<div {{ $attributes->merge([
    'class' => 'card',
]) }}>
    <div class="card-body">
        @foreach ($this->colors as $color)
            <div class="space-y-3">
                <div class="text-sm font-semibold capitalize">
                    {{ $color }}
                </div>
                <div class="p-2 rounded-lg shadow bg-{{ $color }}">
                    bg-{{ $color }}
                </div>
                <div class="p-2 rounded-lg shadow text-{{ $color }}">
                    text-{{ $color }}
                </div>
                <div class="p-2 rounded-lg shadow bg-{{ $color }} text-bg-{{ $color }}">
                    text-bg-{{ $color }}
                </div>
            </div>
            <hr class="my-4">
        @endforeach
    </div>
</div>
