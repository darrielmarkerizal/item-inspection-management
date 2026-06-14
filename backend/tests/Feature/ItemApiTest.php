<?php

it('returns items with nested lots and master relations', function () {
    $response = $this->getJson('/api/v1/items')->assertOk();

    expect($response->json('data'))->toHaveCount(10);

    $response->assertJsonStructure([
        'data' => [[
            'id',
            'code',
            'description',
            'unit',
            'category' => ['id', 'name'],
            'lots' => [[
                'id',
                'lot_no',
                'available_qty',
                'allocation',
                'owner',
                'condition',
            ]],
        ]],
    ]);
});

it('provides an item with multiple lots for the cascading filter', function () {
    $response = $this->getJson('/api/v1/items');

    $item = collect($response->json('data'))->firstWhere('code', '6203-00640-03-PQ-03-XAS-001');

    expect($item['lots'])->toHaveCount(3);
    expect(collect($item['lots'])->pluck('condition.name')->unique()->values()->all())
        ->toContain('Good', 'Quarantine');
});
