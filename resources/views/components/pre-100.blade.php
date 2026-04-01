@props([
    'object' => null,
    'class' => null,
])
@php
    $class = css_classes(['pre-100', $class]);
@endphp
@pre($object, $class)
