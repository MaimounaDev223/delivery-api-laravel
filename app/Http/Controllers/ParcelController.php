<?php

namespace App\Http\Controllers;

use App\Models\Parcel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ParcelController
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:50',
            'destination_address' => 'required|string',
        ]);

        $validated['tracking_code'] = 'TRK-' . strtoupper(Str::random(8));
        $validated['status'] = 'pending';

        $parcel = Parcel::create($validated);

        return response()->json([
            'data' => $parcel
        ], 201);
    }

    public function show(string $tracking_code)
    {
        $parcel = Parcel::where('tracking_code', $tracking_code)->firstOrFail();

        return response()->json([
            'data' => $parcel
        ], 200);
    }

    public function updateStatus(Request $request, string $tracking_code)
{
    $validated = $request->validate([
        'status' => 'required|string|in:pending,in_transit,delivered,cancelled',
    ]);

    $parcel = Parcel::where('tracking_code', $tracking_code)->firstOrFail();
    $parcel->update([
        'status' => $validated['status'],
    ]);

    return response()->json([
        'message' => 'Statut mis à jour avec succès',
        'data' => $parcel
    ], 200);
}

    public function destroy(Parcel $parcel)
    {
        //
    }
}