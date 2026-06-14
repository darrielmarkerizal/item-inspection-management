<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Inventory\Database\Seeders\InventoryDatabaseSeeder;
use Modules\MasterData\Database\Seeders\MasterDataDatabaseSeeder;

pest()->extend(Tests\TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function () {
        $this->seed([
            MasterDataDatabaseSeeder::class,
            InventoryDatabaseSeeder::class,
        ]);
    })
    ->in('Feature');

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

function inspectionPayload(array $overrides = []): array
{
    return array_merge([
        'service_type' => 'new_arrival',
        'inspection_type_id' => 1,
        'scope_of_work_id' => 1,
        'location_id' => 1,
        'customer_id' => 1,
        'related_to' => 'CO-02023-001',
        'dvc_code' => '1100049832',
        'charge_to_customer' => true,
        'items' => [[
            'item_id' => 1,
            'lots' => [
                ['item_lot_id' => 1, 'qty_required' => 2, 'inspection_required' => true],
            ],
        ]],
    ], $overrides);
}

function createInspection(array $overrides = []): int
{
    return test()->postJson('/api/v1/inspections', inspectionPayload($overrides))->json('data.id');
}

function createInspectionWithStatus(string $status): int
{
    $id = createInspection();

    if (in_array($status, ['for_review', 'completed'], true)) {
        test()->patchJson("/api/v1/inspections/{$id}/status", ['status' => 'for_review']);
    }

    if ($status === 'completed') {
        test()->patchJson("/api/v1/inspections/{$id}/status", ['status' => 'completed']);
    }

    return $id;
}
