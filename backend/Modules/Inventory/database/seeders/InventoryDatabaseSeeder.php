<?php

namespace Modules\Inventory\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Support\CsvImporter;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\ItemLot;
use Modules\MasterData\Models\Allocation;
use Modules\MasterData\Models\Condition;
use Modules\MasterData\Models\ItemCategory;
use Modules\MasterData\Models\Owner;

class InventoryDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $dir = dirname(__DIR__).'/data/';

        $categories = ItemCategory::pluck('id', 'code');

        foreach (CsvImporter::read($dir.'items.csv') as $row) {
            Item::updateOrCreate(
                ['code' => $row['code']],
                [
                    'item_category_id' => $categories[$row['category_code']] ?? null,
                    'description' => $row['description'],
                    'unit' => $row['unit'],
                ]
            );
        }

        $items = Item::pluck('id', 'code');
        $allocations = Allocation::pluck('id', 'name');
        $owners = Owner::pluck('id', 'name');
        $conditions = Condition::pluck('id', 'name');

        foreach (CsvImporter::read($dir.'item_lots.csv') as $row) {
            ItemLot::updateOrCreate(
                ['lot_no' => $row['lot_no']],
                [
                    'item_id' => $items[$row['item_code']] ?? null,
                    'allocation_id' => $allocations[$row['allocation']] ?? null,
                    'owner_id' => $owners[$row['owner']] ?? null,
                    'condition_id' => $conditions[$row['condition']] ?? null,
                    'available_qty' => $row['available_qty'],
                ]
            );
        }
    }
}
