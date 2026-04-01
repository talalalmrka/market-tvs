<div {{ $attributes->merge([
    'class' => css_classes(['card']),
]) }}>
    <div class="card-header">
        <div class="card-title text-primary flex-space-2">
            <i class="icon fg-plus"></i>
            <span>{{ __('Create menu') }}</span>
        </div>
    </div>
    <div class="card-body">
        <form method="post" wire:submit="create">
            <div
                class="form-control-container sm flex items-center {{ css_classes(['error' => $errors->has('name')]) }}">
                <input type="text" wire:model.live="name"
                    class="form-control sm rounded-e-none {{ css_classes(['error' => $errors->has('name')]) }}"
                    placeholder="{{ __('Menu name') }}">
                <button type="submit" class="btn btn-primary rounded-s-none h-full"
                    @if (empty($name)) disabled @endif>
                    <i class="icon fg-plus"></i>
                    <span wire:loading.remove wire:target="create">{{ __('Create') }}</span>
                    <fgx:loader wire:loading wire:target="create" />
                </button>
            </div>
            <fgx:error id="name" />
            <fgx:status id="create" soft size="xs" class="mt-2" />
        </form>
    </div>
</div>
