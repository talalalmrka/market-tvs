<div>
    <div class="card">
        <div class="card-header text-primary">
            <div class="card-title flex items-center gap-2">
                <div class="flex-1 flex items-center gap-2">
                    @icon('bi-box-arrow-up')
                    <span>{{ __('Export') }}</span>
                </div>
                <button wire:click.prevent="initExportData" type="button" class="btn btn-primary btn-xs">
                    <i class="icon bi-arrow-repeat"></i>
                    <span wire:loading.remove wire:target="initExportData">{{ __('Refresh') }}</span>
                    <i class="icon fg-loader-dots-scale" wire:loading wire:target="initExportData"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <fgx:label for="" :label="__('Copy export data')" />
            <div class="relative">
                <fgx:textarea id="exportData" class="text-sm"
                    wire:model.live="exportData" readonly disabled />
                <button type="button" class="absolute top-2 end-2 btn btn-sm btn-secondary"
                    x-data="{ copied: false }"
                    x-on:click="navigator.clipboard.writeText($wire.exportData).then(() => { copied = true; setTimeout(() => { copied = false }, 2000) })">
                    <span x-show="!copied">@icon('bi-clipboard')</span>
                    <span x-show="copied">@icon('bi-check-lg')</span>
                    <span x-show="!copied">{{ __('Copy') }}</span>
                    <span x-show="copied">{{ __('Copied!') }}</span>
                </button>
            </div>
        </div>
    </div>
    <form wire:submit="import">
        <div class="card mt-4">
            <div class="card-header text-primary">
                <div class="card-title flex items-center gap-2">
                    @icon('bi-box-arrow-in-down')
                    <span>{{ __('Import') }}</span>
                </div>
            </div>
            <div class="card-body">
                <fgx:textarea id="importData" wire:model.live="importData" :label="__('Import data')" />
            </div>
            <div class="card-footer flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="icon bi-box-arrow-in-down"></i>
                    <span wire:loading.remove wire:target="import">{{ __('Import') }}</span>
                    <i class="icon fg-loader-dots-scale" wire:loading wire:target="import"></i>
                </button>
                <div class="flex-1">
                    <fgx:status id="import" class="alert-soft xs mt-2" />
                </div>
            </div>
        </div>
    </form>
    <div class="card mt-4">
        <div class="card-header text-primary">
            <div class="card-title flex items-center gap-2">
                @icon('bi-eye')
                <span>{{ __('Preview') }}</span>
            </div>
        </div>
        <div class="card-body">
            <fgx:status id="preview" class="alert-soft xs mt-2" />
            <div class="max-h-96 overflow-y-auto">
                @pre($this->preview)
            </div>

        </div>
    </div>
</div>
