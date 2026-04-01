<x-settings-page>
    <x-settings-card :title="__('Header settings')" class="mb-4"
        body-class="space-y-4">
        <x-settings-row
            input-col-class="col-span-4">
            <fgx:switch
                id="header_code_enabled"
                wire:model.live="header_code_enabled"
                :label="__('Enable header code')" />
        </x-settings-row>
        <x-settings-row
            for="header_code"
            :label="__('Header code')">
            <fgx:textarea
                id="header_code"
                wire:model.live="header_code"
                :directionButtons="true" />
        </x-settings-row>
    </x-settings-card>

    <x-settings-card :title="__('Footer settings')" class="mb-4"
        body-class="space-y-4">
        <x-settings-row input-col-class="col-span-4">
            <fgx:switch
                id="backtop_enabled"
                wire:model.live="backtop_enabled"
                :label="__('Enable back top button')" />
        </x-settings-row>
        <x-settings-row
            for="footer_copyrights"
            :label="__('Footer copyrights')">
            <fgx:textarea
                id="footer_copyrights"
                wire:model.live="footer_copyrights"
                :directionButtons="true"
                :info="__('Supported shortcodes (:name = Site name, :link = Site link, :description Site description, :year = Current year).')" />
        </x-settings-row>
        <x-settings-row input-col-class="col-span-4">
            <fgx:switch
                id="footer_code_enabled"
                wire:model.live="footer_code_enabled"
                :label="__('Enable footer code')" />
        </x-settings-row>
        <x-settings-row
            for="footer_code"
            :label="__('Footer code')">
            <fgx:textarea
                id="footer_code"
                wire:model.live="footer_code"
                :directionButtons="true" />
        </x-settings-row>
    </x-settings-card>

    <x-settings-card :title="__('Custom css code')" class="mb-4"
        body-class="space-y-4">
        <x-settings-row
            input-col-class="col-span-4">
            <fgx:switch
                id="custom_css_enabled"
                wire:model.live="custom_css_enabled"
                :label="__('Enable custom css')" />
        </x-settings-row>
        <x-settings-row
            for="custom_css"
            :label="__('Custom css code')">
            <fgx:textarea
                id="custom_css"
                wire:model.live="custom_css"
                :directionButtons="true" />
        </x-settings-row>
    </x-settings-card>

    <x-settings-card :title="__('Custom javascript code')" class="mb-4"
        body-class="space-y-4">
        <x-settings-row
            input-col-class="col-span-4">
            <fgx:switch
                id="custom_js_enabled"
                wire:model.live="custom_js_enabled"
                :label="__('Enable custom js')" />
        </x-settings-row>
        <x-settings-row
            for="custom_js"
            :label="__('Custom js code')">
            <fgx:textarea
                id="custom_js"
                wire:model.live="custom_js"
                :directionButtons="true" />
        </x-settings-row>
    </x-settings-card>

    <x-settings-card :title="__('Colors')" class="mb-4"
        body-class="space-y-4">
        <x-settings-row
            for="color_primary"
            :label="__('Color primary')">
            <fgx:select
                id="color_primary"
                wire:model.live="color_primary"
                :options="$this->colorOptions" />
        </x-settings-row>
        <x-settings-row
            for="color_secondary"
            :label="__('Color secondary')">
            <fgx:select
                id="color_secondary"
                wire:model.live="color_secondary"
                :options="$this->colorOptions" />
        </x-settings-row>
        <x-settings-row
            for="color_accent"
            :label="__('Color accent')">
            <fgx:select
                id="color_accent"
                wire:model.live="color_accent"
                :options="$this->colorOptions" />
        </x-settings-row>
    </x-settings-card>
</x-settings-page>
