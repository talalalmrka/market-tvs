<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Database\Seeders\SettingSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $settings = Setting::paginate($perPage);
        return SettingResource::collection($settings);
    }

    public function seedData(Request $request)
    {
        return response()->json(Setting::seedData());
    }

    public function defaults(Request $request)
    {
        $search = $request->get('search');
        $settings = SettingSeeder::defaults();
        if (!empty($search)) {
            $settings = $settings->filter(function (Setting $setting) use ($search) {
                return Str::contains($setting->key, $search, true) || Str::of($setting->type)->contains($search, true);
            })->values();
        }
        return SettingResource::collection($settings);
        return response()->json($settings);
    }

    public function defaultsItem(Request $request, string $key)
    {
        $setting = SettingSeeder::defaults()->where('key', '=', $key)->first();
        if (!$setting) {
            abort(404);
        }
        return response()->json($setting);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting)
    {
        return new SettingResource($setting);
    }

    /**
     * Display the specified resource.
     */
    public function media(Setting $setting)
    {
        dd($setting->getMedia($setting->key));
        // return new SettingResource($setting);
    }

    public function key(Request $request, string $key)
    {
        // dd(setting($key));
        // $setting = Setting::firstWhere('key', $key);
        // $setting = Setting::query()->firstWhere('key', $key);
        // $setting = Setting::byKey($key);
        $setting = setting($key);
        dd($setting);
    }
    public function type(Request $request, string $key)
    {

        $type = get_option_type($key);
        dd($type);
    }
    public function collections(Request $request, string $key)
    {
        $setting = Setting::withKey($key);
        dd($setting?->getRegisteredMediaCollections());
    }
    public function previews(Request $request, string $key)
    {

        $setting = Setting::withKey($key);
        dd($setting?->getPreviews());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Setting $setting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
