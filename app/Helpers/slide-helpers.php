<?php

use App\Models\Slide;

if (!function_exists('instance_slide')) {
    function instance_slide($object)
    {
        return $object instanceof Slide;
    }
}
if (!function_exists('slide_options')) {
    function slide_options()
    {
        $screens = Slide::all();
        return $screens->map(function (Slide $slide) {
            return [
                'value' => $slide->id,
                'label' => $slide->name,
            ];
        })->toArray();
    }
}

if (!function_exists('slide_transition_options')) {
    function slide_transition_options()
    {
        return [
            [
                'label' => __('Random'),
                'value' => __('random'),
                'class' => '',
            ],
            [
                'label' => __('Scale up'),
                'value' => __('scaleUp'),
                'class' => __('animate-scale-up'),
            ],
            [
                'label' => __('Scale down'),
                'value' => __('scaleDown'),
                'class' => __('animate-scale-down'),
            ],
            [
                'label' => __('Rotate X In'),
                'value' => __('rotateXIn'),
                'class' => __('animate-rotate-x-in'),
            ],
            [
                'label' => __('Rotate X Out'),
                'value' => __('rotateXOut'),
                'class' => __('animate-rotate-x-out'),
            ],
            [
                'label' => __('Rotate Y In'),
                'value' => __('rotateYIn'),
                'class' => __('animate-rotate-y-in'),
            ],
            [
                'label' => __('Rotate Y Out'),
                'value' => __('rotateYOut'),
                'class' => __('animate-rotate-y-out'),
            ],
            [
                'label' => __('Slide in right'),
                'value' => __('slideInRight'),
                'class' => __('animate-slide-in-right'),
            ],
            [
                'label' => __('Slide in left'),
                'value' => __('slideInLeft'),
                'class' => __('animate-slide-in-left'),
            ],
            [
                'label' => __('Slide in top'),
                'value' => __('slideInTop'),
                'class' => __('animate-slide-in-top'),
            ],
            [
                'label' => __('Slide in top soft'),
                'value' => __('slideInTopSoft'),
                'class' => __('animate-slide-in-top-soft'),
            ],
            [
                'label' => __('Slide in bottom'),
                'value' => __('slideInBottom'),
                'class' => __('animate-slide-in-bottom'),
            ],
            [
                'label' => __('Slide in bottom soft'),
                'value' => __('slideInBottomSoft'),
                'class' => __('animate-slide-in-bottom-soft'),
            ],
        ];
    }
}

if (!function_exists('slide_transitions')) {
    function slide_transitions(): array
    {
        $transitions = [];
        collect(slide_transition_options())->each(function ($option) use (&$transitions) {
            $transitions[$option['value']] = $option['class'];
        });
        return $transitions;
    }
}

if (!function_exists('slide_transition_values')) {
    function slide_transition_values()
    {
        return arr_map(slide_transition_options(), fn($op) => data_get($op, 'value'));
    }
}

if (!function_exists('edit_slide_modal_title')) {
    function edit_slide_modal_title(array $slot, array $slide)
    {
        return __('Edit :slide - :slot', [
            'slot' => $slot['name'],
            'slide' => $slide['name'],
        ]);
    }
}
