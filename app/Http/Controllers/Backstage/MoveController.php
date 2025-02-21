<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\Moves\StoreRequest;
use App\Http\Requests\Backstage\Moves\UpdateRequest;
use App\Models\Moves;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MoveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Moves::all()->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        return Moves::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Moves $moves)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Moves $moves)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Moves $moves)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Moves $moves)
    {
        //
    }
}
