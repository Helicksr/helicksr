<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLickRequest;
use App\Http\Requests\UpdateLickRequest;
use App\Models\Lick;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class LickController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $paginatedResults = Lick::where('user_id', $request->user()->id)->paginate(10);

        return Inertia::render('Library/Index', [
            'licks' => $paginatedResults->items(),
            'total' => $paginatedResults->total(),
            'perPage' => $paginatedResults->perPage(),
            'currentPage' => $paginatedResults->currentPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Library/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLickRequest $request): RedirectResponse
    {
        $newLick = new Lick();
        $newLick->title = $request->input('title');
        $newLick->tempo = $request->input('tempo');
        $newLick->length = 10; // TODO: calculate length from audio file

        // upload audio file
        if ($request->has('audio') && $request->file('audio') !== null) {
            $newLick->audio_file_path = $request->file('audio')->storePublicly(
                'audio-licks',
            );
        }

        // upload score/tab.
        $newLick->transcription = $request->input('transcription');
        $newLick->tags = $request->input('tags', []);
        $newLick->amp_settings = $request->input('amp_settings', []);

        $newLick->user_id = $request->user()->id;

        $newLick->save();

        session()->flash('flash.banner', 'Lick created successfully!');
        session()->flash('flash.bannerStyle', 'success');
        return to_route('library.show', ['lick' => $newLick]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lick $lick, Request $request): Response
    {
        return Inertia::render('Library/Show', [
            'lick' => $lick,
            'canEdit' => $request->user()->can('update', $lick),
            'canDelete' => $request->user()->can('delete', $lick),
            'canShare' => $request->user()->can('share', $lick),
            'author' => $lick->user->name,
            'sharedWith' => $lick->usersShared,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lick $lick): Response
    {
        return Inertia::render('Library/Edit', [
            'lick' => $lick,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLickRequest $request, Lick $lick): RedirectResponse
    {
        if ($request->has('title')) {
            $lick->title = $request->input('title');
        }

        if ($request->has('tempo')) {
            $lick->tempo = $request->input('tempo');
        }

        // upload audio file
        if ($request->has('audio') && $request->file('audio') !== null) {
            if ($lick->audio_file_path) {
                Storage::delete($lick->audio_file_path);
            }
            $lick->audio_file_path = $request->file('audio')->storePublicly(
                'audio-licks',
            );
        }

        // upload score/tab
        if ($request->has('transcription')) {
            $lick->transcription = $request->input('transcription');
        }

        if ($request->has('tags')) {
            $lick->tags = $request->input('tags');
        }

        if ($request->input('amp_settings')) {
            $lick->amp_settings = $request->input('amp_settings');
        }

        $lick->save();

        session()->flash('flash.banner', 'Lick updated successfully!');
        session()->flash('flash.bannerStyle', 'success');
        return to_route('library.show', ['lick' => $lick]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lick $lick): RedirectResponse
    {
        $lick->delete();

        session()->flash('flash.banner', 'Lick deleted successfully!');
        session()->flash('flash.bannerStyle', 'success');
        return to_route('library.index');
    }
}
