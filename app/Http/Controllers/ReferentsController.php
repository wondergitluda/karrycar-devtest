<?php

namespace App\Http\Controllers;

use App\Models\Referent;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReferentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Referents/Index', [
            'referents' => Referent::with('team')
                ->withCount('shipments')
                ->paginate(50),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Referent $referent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Referent $referent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Referent $referent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Referent $referent)
    {
        //
    }
}
