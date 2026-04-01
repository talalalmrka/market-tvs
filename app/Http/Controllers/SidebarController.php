<?php

namespace App\Http\Controllers;

use App\SidebarItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SidebarController extends Controller
{
    public function index(Request $request)
    {
        $items = SidebarItem::all();
        dd($items);
        return response()->json($items);
    }

    public function flat(Request $request)
    {
        $term = $request->get('term');
        $flat = !empty($term) ? SidebarItem::search($term) : SidebarItem::flat();
        return response()->json($flat);
    }

    public function labels(Request $request)
    {
        $labels = SidebarItem::flat()
            ->map(fn(SidebarItem $item) => $item->label);
        dd($labels);
        return response()->json($flat);
    }

    public function show(Request $request, int $index)
    {
        $items = SidebarItem::all();
        $item = $items->get($index);
        dd($item->toArray());
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        $items = SidebarItem::search($term);
        // dd($items);
        return response()->json($items);
    }
    public function render(Request $request)
    {
        return view('partials.dashboard-sidebar');
        return SidebarItem::sidebar();
    }

    public function renderItem(Request $request, int $index)
    {
        $item = SidebarItem::all()->get($index);
        return $item->render();
    }

    public function routes(Request $request)
    {
        return response()->json(SidebarItem::routes());
    }

    public function settings(Request $request)
    {
        return response()->json(SidebarItem::settings());
    }
    public function settingsFlat(Request $request)
    {
        return response()->json(SidebarItem::flatSettings());
    }

    public function settingsRoutes(Request $request)
    {
        return response()->json(SidebarItem::settingsRoutes());
    }
}
