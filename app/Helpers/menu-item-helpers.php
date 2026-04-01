<?php

use App\Models\MenuItem;

if (! function_exists('menu_item_type_options')) {
    function menu_item_type_options()
    {
        return [
            ...[
                [
                    'label' => __('Custom'),
                    'value' => 'custom',
                ],
            ],
            ...models_with('permalink')->map(fn ($model) => [
                'label' => data_get($model, 'name'),
                'value' => data_get($model, 'id'),
            ])->toArray(),
        ];
    }
}

if (! function_exists('menu_item_model_type_options')) {
    function menu_item_model_type_options()
    {
        return [
            ...[
                'label' => __('Custom'),
                'value' => null,
            ],
            ...models()->map(fn ($model) => [
                'label' => data_get($model, 'name'),
                'value' => data_get($model, 'morph'),
            ])->toArray(),
        ];
    }
}

if (! function_exists('menu_item_options')) {
    function menu_item_options($emptyOption = null)
    {
        $options = collect([]);
        if ($emptyOption) {
            $options->push([
                'label' => $emptyOption,
                'value' => '',
            ]);
        }
        $items = MenuItem::all();
        $items->each(function (MenuItem $item) use ($options) {
            $options->push([
                'label' => $item->name,
                'value' => $item->id,
            ]);
        });

        return $options->toArray();
    }
}
