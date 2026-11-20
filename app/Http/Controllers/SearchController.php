<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request, SearchService $service)
    {
        $term = trim((string) $request->query('q', ''));

        return view('search.index', [
            'term' => $term,
            'results' => $term !== '' ? $service->search($term) : null,
        ]);
    }
}
