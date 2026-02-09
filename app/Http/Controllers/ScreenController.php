<?php

namespace App\Http\Controllers;

use App\Http\Resources\ScreenResource;
use App\Models\Screen;
use App\Models\User;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $screens = Screen::paginate($perPage);
        return ScreenResource::collection($screens);
    }

    /**
     * Display a listing of the resource.
     */
    public function user(User $user, Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $screens = $user->screens()->paginate($perPage);
        return ScreenResource::collection($screens);
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
    public function show(Screen $screen)
    {
        return new ScreenResource($screen);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Screen $screen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Screen $screen)
    {
        //
    }
}
