<x-settings-page>
    <x-settings-card body-class="space-y-4">
        <x-settings-row
            for="font_family"
            :label="__('Font family')">
            <fgx:select
                id="font_family"
                wire:model.live="font_family"
                :options="font_family_options()" />
        </x-settings-row>
        <x-settings-row
            for="font_smoothing"
            :label="__('Font smoothing')">
            <fgx:select
                id="font_smoothing"
                wire:model.live="font_smoothing"
                :options="font_smoothing_options()" />
        </x-settings-row>
        <x-settings-row
            for="font_size"
            :label="__('Font size')">
            <fgx:select
                id="font_size"
                wire:model.live="font_size"
                :options="font_size_options()" />
        </x-settings-row>
        <x-settings-row
            for="font_weight"
            :label="__('Font weight')">
            <fgx:select
                id="font_weight"
                wire:model.live="font_weight"
                :options="font_weight_options()" />
        </x-settings-row>
        <x-settings-row
            for="font_style"
            :label="__('Font style')">
            <fgx:select
                id="font_weight"
                wire:model.live="font_style"
                :options="font_style_options()" />
        </x-settings-row>
        <x-settings-row
            for="font_display"
            :label="__('Font display')">
            <fgx:select
                id="font_display"
                wire:model.live="font_display"
                :options="font_display_options()" />
        </x-settings-row>

    </x-settings-card>
    <x-settings-card :title="$this->previewTitle" class="mt-4">
        <div class="{{ $this->previewClasses }}" style="{{ $this->previewStyles }}">
            <p>
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the
                industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and
                scrambled it to make a type specimen book.
            </p>
            <p dir="rtl">
                هذا النص هو مثال لنص يمكن أن يستبدل في نفس المساحة، لقد تم توليد هذا النص من مولد النص العربى، حيث يمكنك
                أن
                تولد مثل هذا النص أو العديد من النصوص الأخرى إضافة إلى زيادة عدد الحروف التى يولدها التطبيق.
            </p>
        </div>
    </x-settings-card>
</x-settings-page>
