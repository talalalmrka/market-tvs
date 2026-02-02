<div class="{{ css_classes(['card', $class]) }}">
    <div class="card-header">
        <h5 class="card-title">Toast</h5>
    </div>
    <div class="card-body">
        <h5>Type</h5>
        <div class="flex flex-wrap gap-2">
            @foreach ($this->types as $type)
                <button type="button" class="btn btn-primary"
                    wire:click="showToastType('{{ $type }}')">
                    {{ ucfirst($type) }}
                </button>
            @endforeach
        </div>
        <hr class="my-6">
        <h5>Position</h5>
        <div class="flex flex-wrap gap-2">
            @foreach ($this->positions as $position)
                <button type="button" class="btn btn-primary"
                    wire:click="showToastPosition('{{ $position }}')">
                    {{ ucfirst($position) }}
                </button>
            @endforeach
        </div>
        <hr class="my-6">
        <h5>Size</h5>
        <div class="flex flex-wrap gap-2">
            @foreach ($this->sizes as $size)
                <button type="button" class="btn btn-primary"
                    wire:click="showToastSize('{{ $size }}')">
                    {{ ucfirst($size) }}
                </button>
            @endforeach
        </div>
    </div>
</div>
