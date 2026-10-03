<?php

namespace App\Http\Controllers;

use App\Models\Parcel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ParcelController 
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_name' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:50',
            'destination_address' => 'required|string',
        ]);

        // Génération d'un code de suivi unique (ex: TRK-ABC12345)
        $validated['tracking_code'] = 'TRK-' . strtoupper(Str::random(8));
        $validated['status'] = 'pending';

        $parcel = Parcel::create($validated);

        return response()->json([
            'message' => 'Colis enregistré avec succès',
            'data' => $parcel
        ], 201);
    }
}