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
        return Inertia::render('Library', [
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreLickRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLickRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lick  $lick
     * @return \Illuminate\Http\Response
     */
    public function show(Lick $lick)
    {
        //
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
