<?php

namespace App\Http\Controllers;

use App\Models\Lick;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function create(Lick $lick, Request $request)
    {
        // share with users
        if ($request->input('share_target_users')) {
            $target = User::whereIn('id', $request->input('share_target_users'))->get();
            $lick->usersSharedDirectly()->attach($target->pluck('id'));

            // TODO: emit event SharedWithUsers to handle notifications and other effects
        }

        // share with teams
        if ($request->input('share_target_teams')) {
            $target = Team::whereIn('id', $request->input('share_target_teams'))->get();
            $lick->teamsSharedDirectly()->attach($target->pluck('id'));

            // TODO: emit event SharedWithTeams to handle notifications and other effects
        }

        session()->flash('flash.banner', 'Lick shared successfully!');
        session()->flash('flash.bannerStyle', 'success');
        return to_route('library.show', ['lick' => $lick]);
    }
}
