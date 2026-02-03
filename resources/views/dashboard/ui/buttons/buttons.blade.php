<div {{ $attributes->merge([
    'class' => 'card',
]) }}>
    <div class="card-body">
        <div class="flex gap-2 flex-wrap">
            @foreach ($this->buttons as $button)
                <button type="button" class="btn {{ $button['class'] }}">
                    {{ $button['label'] }}
                </button>
            @endforeach
        </div>
    </div>
</div>
