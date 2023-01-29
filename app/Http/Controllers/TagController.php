<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $inputLimit = $request->input('limit', 10);
        $searchTerm = $request->input('search');

        $query = DB::table('tags')
        ->limit($inputLimit > 10 ? 10 : $inputLimit)
        ->orderBy('uses', 'desc');

        if ($searchTerm) {
            $query->where('tag', 'like', "%$searchTerm%");
        }

        return $query->get()->pluck('tag');
    }
}
