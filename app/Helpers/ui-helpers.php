<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

if (! function_exists('ui_page_icon')) {
    function ui_page_icon($id)
    {
        $icons = [
            'colors' => 'bi-palette',
            'backgrounds' => 'bi-palette',
            'buttons' => 'bi-square',
            'modal' => 'bi-window',
            'sortable' => 'bi-sort-down',
            'toast' => 'bi-bell',
            'progress' => 'bi-bar-chart',
            'shimmer' => 'bi-square-half',
            'select_model' => 'bi-mouse-pointer',
            'models' => 'bi-database',
            'alerts' => 'bi-info-circle',
            'alpine-sort' => 'bi-sort-down',
            'menu-group' => 'bi-list',
        ];
        $key = Str::of($id)->replace('-', '_');

        return data_get($icons, $key, 'bi-question-circle');
    }
}
if (! function_exists('ui_pages')) {
    function ui_pages()
    {
        $directories = File::allDirectories(resource_path('views/dashboard/ui'));

        return collect($directories)
            ->filter(fn($value, $key) => ! Str::endsWith(File::basename($value), ' copy'))
            ->values()
            ->map(function ($path) {
                $id = File::basename($path);
                $label = Str::of($id)
                    ->replace('-', ' ')
                    ->title();

                return [
                    'id' => $id,
                    'label' => $label,
                    'icon' => ui_page_icon($id),
                    'view' => "dashboard::ui.{$id}",
                    'route' => "dashboard.ui.{$id}",
                ];
            });
    }
}

if (! function_exists('register_ui_routes')) {
    function register_ui_routes()
    {
        ui_pages()->each(function ($page) {
            $id = data_get($page, 'id');
            $view = data_get($page, 'view');
            $route = data_get($page, 'route');
            Route::livewire($id, $view)->name($route);
        });
    }
}

if (! function_exists('ui_sidebar')) {
    function ui_sidebar()
    {
        ui_pages()->each(function ($page) {
            $route = data_get($page, 'route');
            $label = data_get($page, 'label');
            $icon = data_get($page, 'icon');
            echo view('components.nav-link', [
                'navigate' => true,
                'href' => route($route),
                'label' => $label,
                'icon' => $icon,
                'atts' => [
                    'wire:current.exact' => 'active',
                ],
            ]);
        });
    }
}
