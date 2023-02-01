<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLickRequest;
use App\Http\Requests\UpdateLickRequest;
use App\Models\Lick;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LickController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $paginatedResults = Lick::where('user_id', $request->user()->id)->paginate(10);

        return Inertia::render('Library/index', [
            'licks' => $paginatedResults->items(),
            'total' => $paginatedResults->total(),
            'perPage' => $paginatedResults->perPage(),
            'currentPage' => $paginatedResults->currentPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Library/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLickRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLickRequest $request)
    {
        $newLick = new Lick();
        $newLick->title = $request->input('title');
        $newLick->tempo = $request->input('tempo');
        $newLick->length = 10; // TODO: calculate length from audio file

        // upload audio file
        if ($request->has('audio')) {
            $newLick->audio_file_path = $request->file('audio')->storePublicly(
                'audio-licks',
            );
        }

        // upload score/tab
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
     *
     * @param  \App\Models\Lick  $lick
     * @return \Illuminate\Http\Response
     */
    public function show(Lick $lick)
    {
        return Inertia::render('Library/Show', [
            'lick' => $lick,
            'author' => $lick->user->name,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lick  $lick
     * @return \Illuminate\Http\Response
     */
    public function edit(Lick $lick)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateLickRequest  $request
     * @param  \App\Models\Lick  $lick
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLickRequest $request, Lick $lick)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lick  $lick
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lick $lick)
    {
        //
    }
}
