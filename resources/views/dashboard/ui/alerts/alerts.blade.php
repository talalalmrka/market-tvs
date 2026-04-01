<div {{ $attributes }}>
    <x-card :title="__('Type')">
        <div class="space-y-4">
            <x-alert info>
                This is alert info
            </x-alert>
            <x-alert success>
                This is alert success
            </x-alert>
            <x-alert warning>
                This is alert warning
            </x-alert>
            <x-alert error>
                This is alert error
            </x-alert>
        </div>
    </x-card>

    <x-card :title="__('Outline')" class="mt-6">
        <div class="space-y-4">
            <x-alert outline info>
                This is alert info
            </x-alert>
            <x-alert outline success>
                This is alert success
            </x-alert>
            <x-alert outline warning>
                This is alert warning
            </x-alert>
            <x-alert outline error>
                This is alert error
            </x-alert>
        </div>
    </x-card>

    <x-card :title="__('Soft')" class="mt-6">
        <div class="space-y-4">
            <x-alert soft info>
                This is alert info
            </x-alert>
            <x-alert soft success>
                This is alert success
            </x-alert>
            <x-alert soft warning>
                This is alert warning
            </x-alert>
            <x-alert soft error>
                This is alert error
            </x-alert>
        </div>
    </x-card>

    <x-card :title="__('Size')" class="mt-6">
        <div class="space-y-4">
            <x-alert xxs>
                This is alert xxs
            </x-alert>
            <x-alert xs>
                This is alert xs
            </x-alert>
            <x-alert sm>
                This is alert sm
            </x-alert>
            <x-alert lg>
                This is alert lg
            </x-alert>
            <x-alert xl>
                This is alert xl
            </x-alert>
            <x-alert xxl>
                This is alert xxl
            </x-alert>
        </div>
    </x-card>
</div>
