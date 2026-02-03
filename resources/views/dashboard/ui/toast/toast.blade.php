<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Basic usage</h5>
        </div>
        <div class="card-body">
            <div class="flex flex-wrap gap-2">
                <button wire:click="$dispatch('toast', {message: 'This is simple toast'})" type="button"
                    class="btn btn-primary">
                    Simple Toast
                </button>
                <button
                    wire:click="$dispatch('toast', {message: 'This is long toast This is long toast This is long toast This is long toast This is long toast This is long toast '})"
                    type="button"
                    class="btn btn-primary">
                    Long Toast
                </button>
            </div>
        </div>
    </div>

    <div class="card mt-6">
        <div class="card-header">
            <h5 class="card-title">Type</h5>
        </div>
        <div class="card-body">
            <div class="flex flex-wrap gap-2">
                @foreach ($this->types as $type)
                    <button type="button" class="btn {{ $this->buttonClass($type) }}"
                        wire:click="$dispatch('toast', {
                        message: 'This is toast type: {{ $type }}',
                        options: {type: '{{ $type }}'}
                        })">
                        {{ ucfirst($type) }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card mt-6">
        <div class="card-header">
            <h5 class="card-title">Position</h5>
        </div>
        <div class="card-body">
            <div class="flex flex-wrap gap-2">
                @foreach ($this->positions as $position)
                    <button type="button" class="btn btn-primary"
                        wire:click="$dispatch('toast', {
                        message: 'This is toast position: {{ $position }}',
                        options: {position: '{{ $position }}'}
                        })">
                        {{ $this->positionLabel($position) }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card mt-6">
        <div class="card-header">
            <h5 class="card-title">Size</h5>
        </div>
        <div class="card-body">
            <div class="flex flex-wrap gap-2">
                @foreach ($this->sizes as $size)
                    <button type="button" class="btn btn-primary"
                        wire:click="$dispatch('toast', {
                        message: 'This is toast size: {{ $size }}',
                        options: {size: '{{ $size }}'}
                        })">
                        {{ ucfirst($size) }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
