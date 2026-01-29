<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main>
        @if (isset($title) && $title !== null)
            <h3 class="text-gray-500 dark:text-white text-2xl">{{ $title }}</h3>
        @endif
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
