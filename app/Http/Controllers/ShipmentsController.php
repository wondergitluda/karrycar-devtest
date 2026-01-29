<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShipmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Shipments/Index', [
            'shipments' => Shipment::with('startReferents', 'endReferents', 'team')->paginate(50),
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
    public function show(Shipment $shipment)
    {
        return Inertia::render('Shipments/Show', [
            'shipment' => $shipment->load('referents', 'startReferents', 'endReferents', 'team'),
        ]);
    }

    /**
     * Add a referent to the specified shipment.
     */
    public function addReferent(Request $request, Shipment $shipment)
    {
        // $validated = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'last_name' => 'required|string|max:255',
        //     'email' => 'required|email|max:255',
        //     'phone' => 'required|string|max:20',
        // ]);
        // $validated['team_id'] = $shipment->team_id;

        // $referent = $shipment->referents()->create($validated);

        // return response()->json($referent, 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipment $shipment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment)
    {
        //
    }
}
