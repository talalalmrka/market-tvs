<x-settings-page>
    <x-settings-card
        body-class="space-y-4">
        <x-settings-row
            for="front_type"
            :label="__('Your homepage displays')">
            <fgx:radio
                id="front_type"
                wire:model.live="front_type"
                :options="front_type_options()" />
        </x-settings-row>
        <x-settings-row
            for="front_page"
            :label="__('Homepage')">
            <fgx:select
                id="front_page"
                wire:model.live="front_page"
                :disabled="$front_type !== 'page'"
                :options="page_options()" />
        </x-settings-row>
        <x-settings-row
            for="posts_page"
            :label="__('Posts page')">
            <fgx:select
                id="posts_page"
                wire:model.live="posts_page"
                :disabled="$front_type !== 'page'"
                :options="page_options()" />
        </x-settings-row>
        <x-settings-row
            for="posts_per_page"
            :label="__('Posts per page')">
            <fgx:input
                type="number"
                min="5"
                max="50"
                id="posts_per_page"
                wire:model.live="posts_per_page" />
        </x-settings-row>
        <x-settings-row
            input-col-class="col-span-4">
            <fgx:switch
                id="disable_search_engines"
                wire:model.live="disable_search_engines"
                :label="__('Discourage search engines from indexing this site')" />
        </x-settings-row>
    </x-settings-card>

</x-settings-page>
