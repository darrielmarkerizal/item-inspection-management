<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Inspection\Database\Seeders\InspectionDatabaseSeeder;
use Modules\Inventory\Database\Seeders\InventoryDatabaseSeeder;
use Modules\MasterData\Database\Seeders\MasterDataDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order matters: master data first, then inventory (depends on master),
     * then sample inspections (depend on both).
     */
    public function run(): void
    {
        $this->call([
            MasterDataDatabaseSeeder::class,
            InventoryDatabaseSeeder::class,
            InspectionDatabaseSeeder::class,
        ]);
    }
}
