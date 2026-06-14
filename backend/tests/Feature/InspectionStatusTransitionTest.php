<?php

it('advances an inspection from open to for_review and records history', function () {
    $id = createInspection();

    $this->patchJson("/api/v1/inspections/{$id}/status", ['status' => 'for_review'])
        ->assertOk()
        ->assertJsonPath('data.status.value', 'for_review');

    $this->assertDatabaseHas('inspection_status_histories', [
        'inspection_id' => $id,
        'from_status' => 'open',
        'to_status' => 'for_review',
    ]);
});

it('advances an inspection from for_review to completed', function () {
    $id = createInspectionWithStatus('for_review');

    $this->patchJson("/api/v1/inspections/{$id}/status", ['status' => 'completed'])
        ->assertOk()
        ->assertJsonPath('data.status.value', 'completed');
});

it('rejects skipping from open to completed', function () {
    $id = createInspection();

    $this->patchJson("/api/v1/inspections/{$id}/status", ['status' => 'completed'])
        ->assertStatus(422);
});

it('rejects moving backward from completed', function () {
    $id = createInspectionWithStatus('completed');

    $this->patchJson("/api/v1/inspections/{$id}/status", ['status' => 'open'])
        ->assertStatus(422);
});

it('rejects an invalid status value', function () {
    $id = createInspection();

    $this->patchJson("/api/v1/inspections/{$id}/status", ['status' => 'bogus'])
        ->assertStatus(422);
});
