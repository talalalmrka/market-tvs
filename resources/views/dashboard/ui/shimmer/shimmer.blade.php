<div {{ $attributes->merge([
    'class' => 'card',
]) }}>
    <div class="card-body">
        @foreach ($this->shimmers as $shimmer)
            <div class="text-sm pb-2 font-semibold">{{ $shimmer }}</div>
            <hr class="w-20 border-primary border-2 rounded-lg">
            <div class="{{ $shimmer }} mt-4 mb-6"></div>
        @endforeach
    </div>
</div>
