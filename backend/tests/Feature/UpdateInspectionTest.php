<?php

it('updates an open inspection', function () {
    $id = createInspection();

    $this->putJson("/api/v1/inspections/{$id}", inspectionPayload(['related_to' => 'EDITED']))
        ->assertOk()
        ->assertJsonPath('data.related_to', 'EDITED');
});

it('blocks editing an inspection that is for_review', function () {
    $id = createInspectionWithStatus('for_review');

    $this->putJson("/api/v1/inspections/{$id}", inspectionPayload())
        ->assertStatus(403);
});

it('blocks editing a completed inspection', function () {
    $id = createInspectionWithStatus('completed');

    $this->putJson("/api/v1/inspections/{$id}", inspectionPayload())
        ->assertStatus(403);
});

it('validates the payload when updating', function () {
    $id = createInspection();

    $this->putJson("/api/v1/inspections/{$id}", inspectionPayload(['items' => []]))
        ->assertStatus(422)
        ->assertJsonValidationErrors('items');
});
