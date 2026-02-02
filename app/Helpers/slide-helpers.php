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
            ],
            [
                'label' => __('Scale up'),
                'value' => __('scale-up'),
            ],
            [
                'label' => __('Scale down'),
                'value' => __('scale-down'),
            ],
            [
                'label' => __('Rotate X In'),
                'value' => __('rotatexin'),
            ],
            [
                'label' => __('Rotate X Out'),
                'value' => __('rotatexout'),
            ],
            [
                'label' => __('Rotate Y In'),
                'value' => __('rotateyin'),
            ],
            [
                'label' => __('Rotate Y Out'),
                'value' => __('rotateyout'),
            ],
            [
                'label' => __('Slide in right'),
                'value' => __('slideinright'),
            ],
        ];
    }
}

if (!function_exists('slide_transition_values')) {
    function slide_transition_values()
    {
        return arr_map(slide_transition_options(), fn($op) => data_get($op, 'value'));
    }
}
