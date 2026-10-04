<?php

use App\Models\Parcel;
use Tests\TestCase;


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
    // 1. Préparation (Arrange)
    $parcel = Parcel::create([
        'tracking_code' => 'TRK-TEST1234',
        'sender_name' => 'Maimouna',
        'recipient_name' => 'Amadou',
        'recipient_phone' => '+22370000000',
        'destination_address' => 'Bamako, Mali',
        'status' => 'pending',
    ]);

    // 2. Action (Act)
    $response = $this->getJson("/api/parcels/{$parcel->tracking_code}");

    // 3. Assertion (Assert)
    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'tracking_code' => 'TRK-TEST1234',
                'sender_name' => 'Maimouna',
                'status' => 'pending',
            ]
        ]);
});