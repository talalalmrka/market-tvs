@props(['menu', 'class' => null, 'atts' => []])
{{-- @dump(get_defined_vars()) --}}
<div
    {{ $attributes->merge([
        ...[
            'class' => css_classes(['nav', $menu->class_name => $menu->class_name, $class => $class]),
        ],
        ...$atts,
    ]) }}>
    @if ($menu->items->isNotEmpty())
        @foreach ($menu->items as $item)
            {!! $item->render() !!}
        @endforeach
    @endif
</div>
