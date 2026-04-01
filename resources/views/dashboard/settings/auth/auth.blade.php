<x-settings-page>
    <x-settings-card
        :title="__('Defaults')"
        class="mb-4"
        body-class="space-y-3">
        <x-settings-row for="defaults.guard" :label="__('Auth Guard')">
            <fgx:select
                id="defaults.guard"
                wire:model.live="defaults.guard"
                :options="$this->guardOptions" />
        </x-settings-row>
        <x-settings-row for="defaults.passwords" :label="__('Auth Passwords')">
            <fgx:select
                id="defaults.passwords"
                wire:model.live="defaults.passwords"
                :options="$this->passwordsOptions" />
        </x-settings-row>
        <x-settings-row for="password_timeout" :label="__('Password timeout')">
            <fgx:input
                type="number"
                id="password_timeout"
                wire:model.live="password_timeout" />
        </x-settings-row>
    </x-settings-card>
    <x-settings-card
        :title="__('Guards')"
        class="mb-4"
        body-class="space-y-3">
        @foreach ($guards as $index => $guard)
            <div class="border rounded-2xl p-2">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="col">
                        <fgx:input
                            id="guards.{{ $index }}.name"
                            wire:model.live="guards.{{ $index }}.name"
                            :label="__('Name')" />
                    </div>
                    <div class="col">
                        <fgx:input
                            id="guards.{{ $index }}.driver"
                            wire:model.live="guards.{{ $index }}.driver"
                            :label="__('Driver')" />
                    </div>
                    <div class="col">
                        <fgx:input
                            id="guards.{{ $index }}.provider"
                            wire:model.live="guards.{{ $index }}.provider"
                            :label="__('Provider')" />
                    </div>
                </div>
                <button wire:click="removeGuard({{ $index }})" type="button"
                    class="btn btn-outline-red btn-xs mt-3">
                    <i class="icon bi-trash"></i>
                    <span wire:loading.remove
                        wire:target="removeGuard({{ $index }})">{{ __('Remove') }}</span>
                    <fgx:loader wire:loading wire:target="removeGuard({{ $index }})" />
                </button>
            </div>
        @endforeach
        <button wire:click="addGuard" type="button" class="btn btn-outline-primary btn-sm mt-3">
            <i class="icon fg-plus"></i>
            <span wire:loading.remove wire:target="addGuard">{{ __('Add guard') }}</span>
            <fgx:loader wire:loading wire:target="addGuard" />
        </button>
        <fgx:status id="add_guard" class="alert-soft xs mt-2" />
    </x-settings-card>
    <x-settings-card
        :title="__('Providers')"
        class="mb-4"
        body-class="space-y-3">
        @foreach ($providers as $index => $provider)
            <div class="border rounded-2xl p-2">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="col">
                        <fgx:input
                            id="providers.{{ $index }}.name"
                            wire:model.live="providers.{{ $index }}.name"
                            :label="__('Name')" />
                    </div>
                    <div class="col">
                        <fgx:input
                            id="providers.{{ $index }}.driver"
                            wire:model.live="providers.{{ $index }}.driver"
                            :label="__('Driver')" />
                    </div>
                    <div class="col">
                        <fgx:input
                            id="providers.{{ $index }}.model"
                            wire:model.live="providers.{{ $index }}.model"
                            :label="__('Model')" />
                    </div>
                </div>
                <button wire:click="removeProvider({{ $index }})" type="button"
                    class="btn btn-outline-red btn-xs mt-3">
                    <i class="icon bi-trash"></i>
                    <span wire:loading.remove
                        wire:target="removeProvider({{ $index }})">{{ __('Remove') }}</span>
                    <fgx:loader wire:loading wire:target="removeProvider({{ $index }})" />
                </button>
            </div>
        @endforeach
        <button wire:click="addProvider" type="button" class="btn btn-outline-primary btn-sm mt-3">
            <i class="icon fg-plus"></i>
            <span wire:loading.remove wire:target="addProvider">{{ __('Add provider') }}</span>
            <fgx:loader wire:loading wire:target="addProvider" />
        </button>
        <fgx:status id="add_provider" class="alert-soft xs mt-2" />
    </x-settings-card>
    <x-settings-card
        :title="__('Auth Passwords')"
        class="mb-4"
        body-class="space-y-3">
        @foreach ($passwords as $index => $password)
            <div class="border rounded-2xl p-2">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <div class="col">
                        <fgx:input
                            id="passwords.{{ $index }}.name"
                            wire:model.live="passwords.{{ $index }}.name"
                            :label="__('Name')" />
                    </div>
                    <div class="col">
                        <fgx:input
                            id="passwords.{{ $index }}.provider"
                            wire:model.live="passwords.{{ $index }}.provider"
                            :label="__('Provider')" />
                    </div>
                    <div class="col">
                        <fgx:input
                            id="passwords.{{ $index }}.table"
                            wire:model.live="passwords.{{ $index }}.table"
                            :label="__('Table')" />
                    </div>
                    <div class="col">
                        <fgx:input
                            type="number"
                            id="passwords.{{ $index }}.expire"
                            wire:model.live="passwords.{{ $index }}.expire"
                            :label="__('Expire')" />
                    </div>
                    <div class="col">
                        <fgx:input
                            type="number"
                            id="passwords.{{ $index }}.throttle"
                            wire:model.live="passwords.{{ $index }}.throttle"
                            :label="__('Throttle')" />
                    </div>
                </div>
                <button wire:click="removePassword({{ $index }})" type="button"
                    class="btn btn-outline-red btn-xs mt-3">
                    <i class="icon bi-trash"></i>
                    <span wire:loading.remove
                        wire:target="removePassword({{ $index }})">{{ __('Remove') }}</span>
                    <fgx:loader wire:loading wire:target="removePassword({{ $index }})" />
                </button>
            </div>
        @endforeach
        <button wire:click="addPassword" type="button" class="btn btn-outline-primary btn-sm mt-3">
            <i class="icon fg-plus"></i>
            <span wire:loading.remove wire:target="addPassword">{{ __('Add password') }}</span>
            <fgx:loader wire:loading wire:target="addPassword" />
        </button>
        <fgx:status id="add_password" class="alert-soft xs mt-2" />
    </x-settings-card>
    @pre($this->all(), 'pre-100')
</x-settings-page>
