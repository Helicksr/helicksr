<?php

namespace App\Http\Controllers;

use App\Models\Lick;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SharedLickController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginatedResults = Lick::whereHas(
            'usersSharedDirectly',
            fn ($query) => $query->where('user_id', $request->user()->id),
        )->orWhereHas(
            'teamsSharedDirectly',
            fn ($query) => $query->whereIn('team_id', $request->user()->allTeams()->pluck('id')),
        )->paginate(10);

        return Inertia::render('Shared/Index', [
            'licks' => $paginatedResults->items(),
            'total' => $paginatedResults->total(),
            'perPage' => $paginatedResults->perPage(),
            'currentPage' => $paginatedResults->currentPage(),
        ]);
    }
}