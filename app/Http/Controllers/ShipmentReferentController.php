<?php

namespace App\Http\Controllers;

use App\Models\Referent;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ShipmentReferentController extends Controller
{
    /**
     * List/search referents for the shipment's team.
     */
    public function index(Request $request, Shipment $shipment)
    {
        $query = $request->get('query', '');
        $scope = $request->get('scope', '');

        $referentsQuery = Referent::where('team_id', $shipment->team_id);

        if (!empty($query)) {
            $referentsQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('last_name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%");
            });
        }

        $referents = $referentsQuery->orderBy('name')->limit(10)->get();

        // Get IDs of referents already attached with this scope
        $existingReferentIds = $shipment->referents()
            ->wherePivot('scope', $scope)
            ->pluck('referents.id')
            ->toArray();

        // Mark which referents are already attached
        $referents = $referents->map(function ($referent) use ($existingReferentIds) {
            $referent->already_attached = in_array($referent->id, $existingReferentIds);
            return $referent;
        });

        return response()->json($referents);
    }

    /**
     * Attach an existing referent or create a new one and attach it to the shipment.
     */
    public function store(Request $request, Shipment $shipment)
    {
        $mode = $request->input('mode', 'existing');
        $scope = $request->input('scope');

        if (!in_array($scope, ['start', 'end'])) {
            throw ValidationException::withMessages([
                'scope' => ['Invalid scope. Must be "start" or "end".'],
            ]);
        }

        if ($mode === 'existing') {
            return $this->attachExisting($request, $shipment, $scope);
        }

        return $this->createAndAttach($request, $shipment, $scope);
    }

    /**
     * Detach a referent from the shipment.
     */
    public function destroy(Shipment $shipment, int $referent)
    {
        $shipment->referents()->wherePivot('id', $referent)->detach();

        return response()->json(['success' => true]);
    }

    /**
     * Attach an existing referent to the shipment.
     */
    private function attachExisting(Request $request, Shipment $shipment, string $scope)
    {
        $request->validate([
            'referent_id' => 'required|integer|exists:referents,id',
        ]);

        $referentId = $request->input('referent_id');
        $referent = Referent::findOrFail($referentId);

        if ($referent->team_id !== $shipment->team_id) {
            throw ValidationException::withMessages([
                'referent_id' => ['This referent does not belong to the shipment\'s team.'],
            ]);
        }

        $alreadyAttached = $shipment->referents()
            ->wherePivot('referent_id', $referentId)
            ->wherePivot('scope', $scope)
            ->exists();

        if ($alreadyAttached) {
            throw ValidationException::withMessages([
                'referent_id' => ['This referent is already attached with this scope.'],
            ]);
        }

        $shipment->referents()->attach($referentId, ['scope' => $scope]);

        return response()->json(['success' => true, 'referent' => $referent]);
    }

    /**
     * Create a new referent and attach it to the shipment.
     */
    private function createAndAttach(Request $request, Shipment $shipment, string $scope)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        if (!empty($validated['email'])) {
            $existingReferent = Referent::where('team_id', $shipment->team_id)
                ->where('email', $validated['email'])
                ->first();

            if ($existingReferent) {
                throw ValidationException::withMessages([
                    'email' => ['A referent with this email already exists for this team.'],
                ]);
            }
        }

        $validated['team_id'] = $shipment->team_id;
        $referent = Referent::create($validated);

        $shipment->referents()->attach($referent->id, ['scope' => $scope]);

        return response()->json(['success' => true, 'referent' => $referent], 201);
    }
}
