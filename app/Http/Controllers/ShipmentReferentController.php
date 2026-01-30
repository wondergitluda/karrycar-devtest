<?php

namespace App\Http\Controllers;

use App\Enums\ReferentScope;
use App\Http\Requests\StoreShipmentReferentRequest;
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

        if (! empty($query)) {
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
        $referents->each(fn ($referent) => $referent->already_attached = in_array($referent->id, $existingReferentIds));

        return response()->json($referents);
    }

    /**
     * Attach an existing referent or create a new one and attach it to the shipment.
     */
    public function store(StoreShipmentReferentRequest $request, Shipment $shipment)
    {
        $validated = $request->validated();
        $scope = ReferentScope::from($validated['scope']);

        if ($validated['mode'] === 'existing') {
            return $this->attachExisting($validated, $shipment, $scope);
        }

        return $this->createAndAttach($validated, $shipment, $scope);
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
    private function attachExisting(array $validated, Shipment $shipment, ReferentScope $scope)
    {
        $referent = Referent::findOrFail($validated['referent_id']);

        if ($referent->team_id !== $shipment->team_id) {
            throw ValidationException::withMessages([
                'referent_id' => ['This referent does not belong to the shipment\'s team.'],
            ]);
        }

        $alreadyAttached = $shipment->referents()
            ->wherePivot('referent_id', $referent->id)
            ->wherePivot('scope', $scope->value)
            ->exists();

        if ($alreadyAttached) {
            throw ValidationException::withMessages([
                'referent_id' => ['This referent is already attached with this scope.'],
            ]);
        }

        $shipment->referents()->attach($referent->id, ['scope' => $scope->value]);

        return response()->json(['success' => true, 'referent' => $referent]);
    }

    /**
     * Create a new referent and attach it to the shipment.
     */
    private function createAndAttach(array $validated, Shipment $shipment, ReferentScope $scope)
    {
        // Check for duplicate email within the same team
        $existingReferent = Referent::where('team_id', $shipment->team_id)
            ->where('email', $validated['email'])
            ->first();

        if ($existingReferent) {
            throw ValidationException::withMessages([
                'email' => ['A referent with this email already exists for this team.'],
            ]);
        }

        $referent = Referent::create([
            'name' => $validated['name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'team_id' => $shipment->team_id,
        ]);

        $shipment->referents()->attach($referent->id, ['scope' => $scope->value]);

        return response()->json(['success' => true, 'referent' => $referent], 201);
    }
}
