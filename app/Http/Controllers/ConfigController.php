<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\ConfigItem;
use Config;

class ConfigController extends Controller
{
    public function index(Request $request)
    {
        dd(config()->all());
    }
    public function all(Request $request)
    {
        dd(Config());
    }

    public function path(Request $request, string $path)
    {
        $config = config($path);
        dd(config($path));
    }

    public function raw(Request $request, string $path)
    {
        dd(ConfigItem::raw($path));
    }

    public function flat(Request $request, string $path)
    {
        $prefix = $request->get("prefix");
        $search = $request->get("search");
        $flat = ConfigItem::flat($path, $prefix);
        if (!empty($search)) {
            $flat = $flat->filter(function ($value, $key) use ($search, $prefix) {
                if (!empty($prefix)) {
                    $key = Str::replace("{$prefix}.", "", $key);
                }
                return Str::contains($key, Str::lower($search));
            });
        }
        dd($flat);
    }
    public function items(Request $request, string $path)
    {
        $prefix = $request->get("prefix");
        $search = $request->get("search");
        $query = ConfigItem::all($path, $prefix);
        if (!empty($search)) {
            $query = $query->filter(function ($item) use ($search, $prefix) {
                if (!empty($prefix)) {
                    $key = Str::replace("{$prefix}.", "", $item->id);
                }
                return Str::contains($key, Str::lower($search));
            });
        }
        dd($query);
    }
    public function rules(Request $request, string $path)
    {
        $prefix = $request->get("prefix");
        $rules = ConfigItem::rules($path, $prefix);

        dd($rules);
    }
    public function options(Request $request, string $path)
    {
        // dd(config_options($path));
    }
    public function sidebarItems(Request $request)
    {
        dd(ConfigItem::sidebarItems());
    }

    public function sidebar(Request $request)
    {
        return ConfigItem::sidebar();
    }

    public function files(Request $request)
    {
        // return response()->json(ConfigItem::files());
        dd(ConfigItem::files());
    }

    public function paths(Request $request)
    {
        return response()->json(ConfigItem::paths());
        // dd(ConfigItem::paths());
    }
    public function categoryOptions(Request $request)
    {
        $placeholder = $request->get("placeholder", __('All'));
        return response()->json(ConfigItem::categoryOptions($placeholder));
        // dd(ConfigItem::categoryOptions());
    }

    public function file(Request $request, string $path)
    {
        $lines = ConfigItem::fileLines($path);
        dd($lines);
    }
}
