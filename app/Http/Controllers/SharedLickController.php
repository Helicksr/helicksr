<?php

namespace App\Http\Controllers;

use App\Events\LickShared as LickSharedEvent;
use App\Models\Lick;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class SharedLickController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
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

    public function create(Lick $lick, Request $request): RedirectResponse
    {
        $targetUsers = User::whereIn('id', $request->input('share_target_users', []))->get();
        $targetTeams = Team::whereIn('id', $request->input('share_target_teams', []))->get();

        $lick->usersSharedDirectly()->syncWithoutDetaching($targetUsers->pluck('id'));
        $lick->teamsSharedDirectly()->syncWithoutDetaching($targetTeams->pluck('id'));

        LickSharedEvent::dispatch($lick, $request->user(), $targetUsers, $targetTeams);

        session()->flash('flash.banner', 'Lick shared successfully!');
        session()->flash('flash.bannerStyle', 'success');
        return to_route('library.show', ['lick' => $lick]);
    }
}
