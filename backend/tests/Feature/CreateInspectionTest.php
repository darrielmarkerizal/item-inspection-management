<?php

it('creates an inspection as open with a generated request number', function () {
    $response = $this->postJson('/api/v1/inspections', inspectionPayload());

    $response->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status.value', 'open');

    expect($response->json('data.request_no'))->toMatch('/^REQ-\d{4}-\d{4}$/');
});

it('persists nested items and lots with snapshot values', function () {
    $this->postJson('/api/v1/inspections', inspectionPayload())
        ->assertCreated()
        ->assertJsonPath('data.items.0.lots.0.item_lot_id', 1)
        ->assertJsonPath('data.items.0.lots.0.lot_no', 'MMT306247201');

    $this->assertDatabaseHas('inspection_item_lots', [
        'lot_no' => 'MMT306247201',
        'owner' => 'PT Santosa',
        'condition' => 'Good',
        'qty_required' => 2,
    ]);
});

it('records an initial status history entry', function () {
    $id = createInspection();

    $this->assertDatabaseHas('inspection_status_histories', [
        'inspection_id' => $id,
        'to_status' => 'open',
    ]);
});

it('requires at least one item', function () {
    $this->postJson('/api/v1/inspections', inspectionPayload(['items' => []]))
        ->assertStatus(422)
        ->assertJsonValidationErrors('items');
});

it('requires at least one lot per item', function () {
    $payload = inspectionPayload();
    $payload['items'][0]['lots'] = [];

    $this->postJson('/api/v1/inspections', $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors('items.0.lots');
});

it('rejects a non-existent item', function () {
    $payload = inspectionPayload();
    $payload['items'][0]['item_id'] = 99999;

    $this->postJson('/api/v1/inspections', $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors('items.0.item_id');
});

it('rejects an invalid service type', function () {
    $this->postJson('/api/v1/inspections', inspectionPayload(['service_type' => 'bogus']))
        ->assertStatus(422)
        ->assertJsonValidationErrors('service_type');
});

it('rejects a negative quantity', function () {
    $payload = inspectionPayload();
    $payload['items'][0]['lots'][0]['qty_required'] = -1;

    $this->postJson('/api/v1/inspections', $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors('items.0.lots.0.qty_required');
});

it('rejects a qty that exceeds the available stock', function () {
    $payload = inspectionPayload();
    $payload['items'][0]['lots'][0]['qty_required'] = 999999;

    $this->postJson('/api/v1/inspections', $payload)
        ->assertStatus(422)
        ->assertJsonValidationErrors('items.0.lots.0.qty_required');
});
