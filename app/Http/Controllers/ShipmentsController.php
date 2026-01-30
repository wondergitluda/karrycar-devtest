<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
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
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {
        return Inertia::render('Shipments/Show', [
            'shipment' => $shipment->load('referents', 'startReferents', 'endReferents', 'team'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment)
    {
        $shipment->delete();

        return response()->json(['success' => true]);
    }
}
