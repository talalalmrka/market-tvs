@props([
	'title' => null,
	'icon' => null,
	'bodyClass' => null,
	'class' => 'mt-6',
	'object' => null,
	'preClass' => 'pre-100',
])

<x-card :title="$title" :icon="$icon" class="{{ $class }}" :body-class="$bodyClass">
    @pre($object, $preClass)
</x-card>