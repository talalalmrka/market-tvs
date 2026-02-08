<div {{ $attributes }}>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Basic buttons</h5>
        </div>
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

    <div class="card mt-6">
        <div class="card-header">
            <h5 class="card-title">Circle buttons</h5>
        </div>
        <div class="card-body">
            <div class="flex gap-2 flex-wrap">
                @foreach ($this->circleButtons as $button)
                    <button type="button" class="btn-circle {{ $button['class'] }}">
                        {{ $button['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
