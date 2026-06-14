<?php

it('returns master data with all dropdown keys', function () {
    $this->getJson('/api/v1/master-data')
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'data' => [
                'service_types',
                'statuses',
                'item_categories',
                'inspection_types',
                'inspection_parameters',
                'scopes_of_work',
                'locations',
                'customers',
                'conditions',
                'owners',
                'allocations',
            ],
        ]);
});

it('exposes service type and status enums as options', function () {
    $this->getJson('/api/v1/master-data')
        ->assertJsonPath('data.service_types.0.value', 'new_arrival')
        ->assertJsonCount(3, 'data.statuses');
});

it('embeds parameters inside scopes of work', function () {
    $response = $this->getJson('/api/v1/master-data');

    expect($response->json('data.scopes_of_work.0.parameters'))->toBeArray()->not->toBeEmpty();
});
