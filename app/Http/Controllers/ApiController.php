<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function routes(Request $request, ?string $name = null)
    {
        $method = $request->get('method', 'get');
        $routes = collect(Route::getRoutes()->getRoutes());
        if (! empty($method)) {
            $routes = $routes->filter(function (RoutingRoute $route) use ($method) {
                return in_array(Str::upper($method), $route->methods());
            })->values();
        }
        if (! empty($name)) {
            $routes = $routes->filter(function (RoutingRoute $route) use ($name) {
                return str($route->getName())->contains($name, true);
            })->values();
        }

        // dd($routes);
        return response()->json($routes);
    }

    public function translate(Request $request, string $text)
    {
        $tr = str($text)->transliterate('???')->value();
        $tr = trans($text, [], 'ar');
        dd($tr);
        // dd(__($text));
    }

    public function timezone(Request $request)
    {
        return response()->json(timezone_options());
    }
}
