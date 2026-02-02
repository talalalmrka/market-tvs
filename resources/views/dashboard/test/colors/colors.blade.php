<div {{ $attributes->merge([
    'class' => 'card',
]) }}>
    <div class="card-header">
        <h5 class="card-title">Colors</h5>
    </div>
    <div class="card-body">
        @foreach ($this->colors as $color)
            <div class="text-sm font-semibold capitalize mt-5">
                {{ $color }}
            </div>
            <div class="grid grid-cols-4 md:grid-cols-11 gap-4">
                @foreach ($this->ranges as $range)
                    <div class="col">
                        <div
                            class="h-10 w-full rounded ring ring-inset ring-black/10 dark:ring-1 dark:ring-inset dark:ring-white/10 bg-{{ $color }}-{{ $range }}">

                        </div>
                        <div class="text-xs text-muted text-center mt-1">
                            {{ $range }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</div>
