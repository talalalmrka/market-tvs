<?php

namespace App\Http\Controllers;

use App\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search');
        $translations = Translation::paginate($perPage, $search);
        dd($translations);
        // return $translations;
    }

    public function all(Request $request)
    {
        $search = $request->get('search');
        $query = Translation::search($search);

        dd($query);
        // return $translations;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $locale)
    {
        $translation = Translation::find($locale);
        if (! $translation) {
            abort(404);
        }
        dd($translation);
    }

    /**
     * Display the specified resource.
     */
    public function words(Request $request, string $locale)
    {
        $translation = Translation::find($locale);
        if (! $translation) {
            abort(404);
        }
        $perPage = $request->get('per_page');
        $pageName = $request->get('page_name');
        $search = $request->get('search');
        // $words = $translation->wordsPaginate($perPage, null, $pageName);
        $words = $translation->wordsPaginate($perPage, $search, null, $pageName);

        // $words = $translation->words;
        return dd($words);
    }
}
