<?php

use App\Models\Parcel;

test('un utilisateur peut enregistrer un nouveau colis', function () {
    $data = [
        'sender_name' => 'Maimouna',
        'recipient_name' => 'Amadou',
        'recipient_phone' => '+22370000000',
        'destination_address' => 'Bamako, Mali',
    ];

    $response = $this->postJson('/api/parcels', $data);

    $response->assertStatus(201)
        ->assertJsonPath('data.sender_name', 'Maimouna')
        ->assertJsonPath('data.status', 'pending');

    $this->assertDatabaseHas('parcels', [
        'sender_name' => 'Maimouna',
        'recipient_name' => 'Amadou',
    ]);
});

test('un utilisateur peut consulter les details d un colis avec son code de suivi', function () {
    $parcel = Parcel::create([
        'tracking_code' => 'TRK-TEST1234',
        'sender_name' => 'Maimouna',
        'recipient_name' => 'Amadou',
        'recipient_phone' => '+22370000000',
        'destination_address' => 'Bamako, Mali',
        'status' => 'pending',
    ]);

    $response = $this->getJson("/api/parcels/{$parcel->tracking_code}");

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'tracking_code' => 'TRK-TEST1234',
                'sender_name' => 'Maimouna',
                'status' => 'pending',
            ]
        ]);
});

test('un utilisateur peut mettre a jour le statut d un colis', function () {
    $parcel = Parcel::create([
        'tracking_code' => 'TRK-STATUS123',
        'sender_name' => 'Maimouna',
        'recipient_name' => 'Amadou',
        'recipient_phone' => '+22370000000',
        'destination_address' => 'Bamako, Mali',
        'status' => 'pending',
    ]);

    $response = $this->patchJson("/api/parcels/{$parcel->tracking_code}/status", [
        'status' => 'in_transit',
    ]);

    $response->assertStatus(200)
        ->assertJsonPath('data.status', 'in_transit');

    $this->assertDatabaseHas('parcels', [
        'tracking_code' => 'TRK-STATUS123',
        'status' => 'in_transit',
    ]);

    
});

test('renvoie une erreur 404 si le colis n existe pas', function () {
    $response = $this->getJson('/api/parcels/TRK-INEXISTANT');

    $response->assertStatus(404);
});

test('refuse un statut invalide lors de la mise a jour', function () {
    $parcel = Parcel::create([
        'tracking_code' => 'TRK-VAL123',
        'sender_name' => 'Maimouna',
        'recipient_name' => 'Amadou',
        'recipient_phone' => '+22370000000',
        'destination_address' => 'Bamako, Mali',
        'status' => 'pending',
    ]);

    // On tente d'envoyer un statut non autorisé
    $response = $this->patchJson("/api/parcels/{$parcel->tracking_code}/status", [
        'status' => 'statut_inconnu',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['status']);
});