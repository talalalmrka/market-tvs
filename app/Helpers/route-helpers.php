<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

if (!function_exists('routes')) {
    /**
     * get all routes
     * @param array|null $filters
     * @return Illuminate\Support\Collection
     */
    function routes($filters = null)
    {
        $filters ??= [];
        $search = data_get($filters, 'search');
        $prefix = data_get($filters, 'prefix');
        $method = data_get($filters, 'method');
        $routes = collect(Route::getRoutes()->getRoutes());
        if (!empty($search)) {
            $routes = $routes->filter(function ($route) use ($search) {
                return Str::contains($route->getName(), $search);
            })->values();
        }

        if (!empty($prefix)) {
            $routes = $routes->filter(function ($route) use ($prefix) {
                return Str::startsWith($route->uri(), $prefix);
            })->values();
        }

        if (!empty($method)) {
            $routes = $routes->filter(function ($route) use ($method) {
                return in_array(Str::upper($method), $route->methods());
            })->values();
        }
        return $routes;
    }
}
