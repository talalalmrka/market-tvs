<x-settings-page>
    <x-settings-card :title="__('Site information')" icon="bi-info-circle" body-class="space-y-4">
        <x-settings-row for="name" :label="__('Site name')">
            <fgx:input id="name" wire:model.live="name" />
        </x-settings-row>
        <x-settings-row for="description" :label="__('Description')">
            <fgx:textarea id="description" wire:model.live="description" />
        </x-settings-row>
        <x-settings-row for="url" :label="__('Url')">
            <fgx:input id="url" wire:model.live="url" />
        </x-settings-row>
        <x-settings-row for="admin_email" :label="__('Admin email')">
            <fgx:input id="admin_email" wire:model.live="admin_email" />
        </x-settings-row>
    </x-settings-card>

    <x-settings-card :title="__('Logo & favicon')" icon="bi-image" class="mt-4" body-class="space-y-4">
        <x-settings-row for="logo" :label="__('Logo')">
            <fgx:file id="logo" wire:model.live="logo" :previews="$this->getPreviews('logo')" />
        </x-settings-row>
        <x-settings-row for="logo_light" :label="__('Logo light')">
            <fgx:file id="logo_light" wire:model.live="logo_light" :previews="$this->getPreviews('logo_light')" />
        </x-settings-row>
        <x-settings-row for="logo_width" :label="__('Logo width (px)')">
            <fgx:input type="number" id="logo_width" wire:model.live="logo_width" />
        </x-settings-row>
        <x-settings-row for="logo_height" :label="__('Logo height (px)')">
            <fgx:input type="number" id="logo_height" wire:model.live="logo_height" />
        </x-settings-row>
        <x-settings-row input-col-class="col-span-4">
            <fgx:switch id="logo_label_enabled" wire:model.live="logo_label_enabled"
                :label="__('Show site name with logo')" />
        </x-settings-row>
        <x-settings-row for="favicon" :label="__('Favicon')">
            <fgx:file id="favicon" wire:model.live="favicon" :previews="$this->getPreviews('favicon')" />
        </x-settings-row>
    </x-settings-card>

    <x-settings-card :title="__('Language & Region')" icon="bi-translate" class="mt-4 overflow-visible" body-class="space-y-4">
        <x-settings-row for="locale" :label="__('Language')">
            {{-- <fgx:select id="locale" wire:model.live="locale" :options="locale_options()" /> --}}
            <x-rich-select id="locale" wire:model.live="locale" :options="locale_options()" />
        </x-settings-row>
        <x-settings-row for="fallback_locale" :label="__('Fallback language')">
            {{-- <fgx:select id="fallback_locale" wire:model.live="fallback_locale" :options="locale_options()" /> --}}
            <x-rich-select id="fallback_locale" wire:model.live="fallback_locale" :options="locale_options()" />
        </x-settings-row>
        <x-settings-row for="faker_locale" :label="__('Faker language')">
            {{-- <fgx:select id="faker_locale" wire:model.live="faker_locale" :options="locale_options()" /> --}}
            <x-rich-select id="faker_locale" wire:model.live="faker_locale" :options="locale_options()" />
        </x-settings-row>
        <x-settings-row for="timezone" :label="__('Timezone')">
            {{-- <fgx:select id="timezone" wire:model.live="timezone" :options="timezone_options()"
                :info="$this->currentDateFormatted('j F Y - h:i a')" /> --}}
            <x-rich-select id="timezone" wire:model.live="timezone" :options="timezone_options()"
                :info="$this->currentDateFormatted('j F Y - h:i a')" />
            <span class="text-xs text-muted">{{ $timezone }}</span>
        </x-settings-row>
        <x-settings-row for="date_format" :label="__('Date format')">
            <fgx:input id="date_format" wire:model.live="date_format"
                :info="$this->currentDateFormatted($date_format)" />
        </x-settings-row>
        <x-settings-row for="datatable_date_format" :label="__('Datatable Date format')">
            <fgx:input id="datatable_date_format" wire:model.live="datatable_date_format"
                :info="$this->currentDateFormatted($datatable_date_format)" />
        </x-settings-row>
    </x-settings-card>

    <x-settings-card :title="__('Encryption')" icon="bi-key" class="mt-4" body-class="space-y-4">
        <x-settings-row for="cipher" :label="__('Encryption cipher')">
            <fgx:select id="cipher" wire:model.live="cipher" :options="app_cipher_options()" />
        </x-settings-row>
        <x-settings-row for="key" :label="__('Encryption Key')">
            <fgx:input id="key" wire:model.live="key" />
        </x-settings-row>
    </x-settings-card>

    <x-settings-card :title="__('Environment')" icon="bi-gear" class="mt-4" body-class="space-y-4">
        <x-settings-row for="env" :label="__('Environment type')">
            <fgx:select id="env" wire:model.live="env" :options="app_env_options()" />
        </x-settings-row>
        <x-settings-row input-col-class="col-span-4">
            <fgx:switch id="debug" wire:model.live="debug"
                :label="__('Enable debug')" />
        </x-settings-row>
        <x-settings-row input-col-class="col-span-4">
            <fgx:switch id="eruda_enabled" wire:model.live="eruda_enabled"
                :label="__('Enable eruda')" :checked="boolval($eruda_enabled)" />
        </x-settings-row>
    </x-settings-card>

    <x-settings-card :title="__('Maintenance mode')" icon="bi-tools" class="mt-4" body-class="space-y-4">
        <x-settings-row for="maintenance.driver" :label="__('Maintenance driver')">
            <fgx:select id="maintenance.driver" wire:model.live="maintenance.driver"
                :options="app_maintenance_driver_options()" />
        </x-settings-row>
        <x-settings-row for="maintenance.store" :label="__('Maintenance store')">
            <fgx:select id="maintenance.store" wire:model.live="maintenance.store"
                :options="app_maintenance_store_options()" />
        </x-settings-row>
    </x-settings-card>

</x-settings-page>
