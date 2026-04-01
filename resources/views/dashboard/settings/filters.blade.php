<div class="inline-flex items-center gap-1">
    <label for="type" class="form-label mb-0">{{ __('Type:') }}</label>
    <fgx:select id="type" class="xs pill min-w-32" wire:model.live.debounce.300ms="type" :options="$typeOptions" />
</div>
<div class="inline-flex items-center gap-1">
    <label for="type" class="form-label mb-0">{{ __('Category:') }}</label>
    <fgx:select id="category" class="xs pill min-w-32" wire:model.live.debounce.300ms="category"
        :options="$categoryOptions" />
</div>
