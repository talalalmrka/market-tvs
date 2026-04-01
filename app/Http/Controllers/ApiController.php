<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

class ApiController extends Controller
{
    public function routes(Request $request, ?string $name = null)
    {
        /* $search = $request->get('search');
        $prefix = $request->get('prefix');
        $method = $request->get('method');
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
        } */

        /* return $routes->map(function ($route) {
            return [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'methods' => $route->methods(),
                'action' => $route->getActionName(),
            ];
        }); */

        // $routes = routes($request->all(['search', 'method', 'prefix']));
        $method = $request->get('method', 'get');
        $routes = collect(Route::getRoutes()->getRoutes());
        /* ->map(function ($route) {
            return [
                'uri' => $route->uri(),
                'name' => $route->getName(),
                'methods' => $route->methods(),
                'action' => $route->getActionName(),
            ];
        }); */
        if (!empty($method)) {
            $routes = $routes->filter(function (RoutingRoute $route) use ($method) {
                return in_array(Str::upper($method), $route->methods());
            })->values();
        }
        if (!empty($name)) {
            $routes = $routes->filter(function (RoutingRoute $route) use ($name) {
                return str($route->getName())->contains($name, true);
            })->values();
        }
        // dd($routes);
        return response()->json($routes);
    }

    public function translate(Request $request, string $text)
    {
        $tr = Str::of($text)->transliterate();
        dd(__($text));
    }
}
