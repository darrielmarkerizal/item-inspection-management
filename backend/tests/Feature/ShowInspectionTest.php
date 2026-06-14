<?php

it('shows the full inspection detail', function () {
    $id = createInspection();

    $this->getJson("/api/v1/inspections/{$id}")
        ->assertOk()
        ->assertJsonPath('data.id', $id)
        ->assertJsonStructure([
            'data' => [
                'request_no',
                'service_type',
                'scope_of_work' => ['parameters'],
                'items' => [['lots']],
                'charges',
                'status',
            ],
        ]);
});

it('omits status histories by default and includes them on demand', function () {
    $id = createInspection();

    $without = $this->getJson("/api/v1/inspections/{$id}");
    expect($without->json('data'))->not->toHaveKey('status_histories');

    $with = $this->getJson("/api/v1/inspections/{$id}?include=statusHistories");
    expect($with->json('data.status_histories'))->toBeArray()->not->toBeEmpty();
});

it('returns 404 for a missing inspection', function () {
    $this->getJson('/api/v1/inspections/99999')->assertStatus(404);
});

it('rejects a disallowed include', function () {
    $id = createInspection();

    $this->getJson("/api/v1/inspections/{$id}?include=secret")->assertStatus(400);
});
