<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ModelsController extends Controller
{
    public function index(Request $request)
    {
        $models = models();
        // $models = all_models();
        dd($models);

        return response()->json($models);
    }

    public function files(Request $request)
    {
        $models = models_files();
        // return response()->json($models);
        dd($models);
    }

    public function objects(Request $request)
    {
        $models = models()->map(function ($model) {
            return app(data_get($model, 'morph'));
            // ->first();
        });
        // return response()->json($models);
        dd($models);
    }

    public function show(Request $request, string $table)
    {
        $model = models()->filter(function ($model) use ($table) {
            return data_get($model, 'table') === $table;
        })->first();
        dd($model);
    }

    public function columns(Request $request, string $table)
    {
        $columns = collect(Schema::getColumnListing($table))->map(fn ($column) => [
            'name' => $column,
            'type' => Schema::getColumnType($table, $column),
        ]);
        dd($columns);
    }
}
