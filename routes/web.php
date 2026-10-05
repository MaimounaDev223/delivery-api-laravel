<?php

use Illuminate\Support\Facades\Route;
use App\Models\Parcel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Page publique de recherche
Route::get('/', function (Request $request) {
    $parcel = null;
    $error = null;

    if ($request->has('tracking_code')) {
        $found = Parcel::where('tracking_code', $request->tracking_code)->first();
        if ($found) {
            $parcel = $found->toArray();
        } else {
            $error = "Aucun colis trouvé avec ce code de suivi.";
        }
    }

    return view('tracking', compact('parcel', 'error'));
});

// Vue Espace Admin (Liste des colis)
Route::get('/admin', function () {
    $parcels = Parcel::latest()->get();
    return view('admin', compact('parcels'));
});

// Création d'un nouveau colis depuis l'Admin
Route::post('/admin/parcels', function (Request $request) {
    $validated = $request->validate([
        'sender_name' => 'required|string|max:255',
        'recipient_name' => 'required|string|max:255',
        'recipient_phone' => 'required|string|max:20',
        'destination_address' => 'required|string|max:255',
    ]);

    $validated['tracking_code'] = 'TRK-' . strtoupper(Str::random(8));
    $validated['status'] = 'pending';

    Parcel::create($validated);

    return redirect('/admin')->with('success', 'Colis créé avec succès ! Code : ' . $validated['tracking_code']);
});

// Modification de statut depuis l'Admin
Route::patch('/admin/parcels/{tracking_code}/status', function (Request $request, string $tracking_code) {
    $validated = $request->validate([
        'status' => 'required|string|in:pending,in_transit,delivered,cancelled',
    ]);

    $parcel = Parcel::where('tracking_code', $tracking_code)->firstOrFail();
    $parcel->update(['status' => $validated['status']]);

    return redirect('/admin')->with('success', 'Statut du colis mis à jour avec succès !');
});