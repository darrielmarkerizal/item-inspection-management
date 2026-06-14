<?php

use Modules\MasterData\Models\InspectionParameter;
use Modules\MasterData\Models\ScopeOfWork;

it('creates a scope of work with parameters', function () {
    $parameterIds = InspectionParameter::take(2)->pluck('id')->all();

    $response = $this->postJson('/api/v1/scopes-of-work', [
        'name' => 'Custom Inspection Scope',
        'service_type' => 'maintenance',
        'description' => 'Custom scope created from the form',
        'parameter_ids' => $parameterIds,
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.name', 'Custom Inspection Scope')
        ->assertJsonPath('data.service_type', 'maintenance');

    expect($response->json('data.parameters'))->toHaveCount(2);
    $this->assertDatabaseHas('scopes_of_work', ['name' => 'Custom Inspection Scope']);
});

it('rejects a duplicate scope name', function () {
    $existing = ScopeOfWork::first();

    $this->postJson('/api/v1/scopes-of-work', [
        'name' => $existing->name,
        'service_type' => 'new_arrival',
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('name');
});

it('rejects an invalid service type when creating a scope', function () {
    $this->postJson('/api/v1/scopes-of-work', [
        'name' => 'Another Scope',
        'service_type' => 'bogus',
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('service_type');
});
