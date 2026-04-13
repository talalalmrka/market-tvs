@props([
    'title' => null,
])
<x-layouts::app.curve :title="$title" :showTitle="false" :showDescription="false">
    <x-slot name="script">
        @vite(['resources/js/dashboard.js'])
        @livewireScriptConfig
    </x-slot>
    <x-slot name="style">
        @vite(['resources/css/app.css'])
        @livewireStyles
    </x-slot>
    <div
        class="bg-white/80 dark:bg-gray-700/80 w-80 md:w-96 mx-auto p-4 z-20 rounded-3xl shadow mb-5 absolute start-1/2 -translate-x-1/2 -mt-20 md:-mt-40">
        <div class="text-center">
            <h5
                class="text-3xl font-semibold text-gradient from-primary to-pink dark:from-white dark:to-pink text-center">
                {{ $title }}
            </h5>
        </div>
        <h1>This is auth</h1>
        {{ $slot }}
    </div>
</x-layouts::app.curve>
