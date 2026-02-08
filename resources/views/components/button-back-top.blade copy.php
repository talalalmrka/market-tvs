@props([
    'id' => 'back-top-button',
    'class' => null,
    'atts' => [],
])
<button x-data="BackTopButton"
    {{ $attributes->merge([
        ...[
            'x-collapse' => '',
            'x-show' => 'show',
            'x-bind' => 'button',
            'class' => css_classes([
                'btn-circle btn-circle-orange opacity-80 hover:opacity-100 fixed z-40 w-10 h-10',
                $class => $class,
            ]),
        ],
        ...$atts,
    ]) }}
    type="button" role="button" aria-label="{{ __('Back top') }}">
    <i class="icon bi-chevron-up"></i>
</button>
