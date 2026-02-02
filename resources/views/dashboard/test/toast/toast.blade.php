<div {{ $attributes->merge([
    'class' => 'card',
]) }}
    x-data="{
        showToast() {
            this.$wire.dispatch('toast', [{
                'message': 'this is dispatched toast',
            }]);
        }
    }">
    <div class="card-header">
        <h5 class="card-title">Toast</h5>
    </div>
    <div class="card-body">
        <h5>Basic usage</h5>
        <button x-on:click="showToast" type="button" class="btn btn-primary">
            Show toast
        </button>
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
