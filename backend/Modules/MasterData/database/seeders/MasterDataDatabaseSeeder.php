<?php

namespace Modules\MasterData\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Support\CsvImporter;
use Modules\MasterData\Models\Allocation;
use Modules\MasterData\Models\Condition;
use Modules\MasterData\Models\Customer;
use Modules\MasterData\Models\InspectionParameter;
use Modules\MasterData\Models\InspectionType;
use Modules\MasterData\Models\ItemCategory;
use Modules\MasterData\Models\Location;
use Modules\MasterData\Models\Owner;
use Modules\MasterData\Models\ScopeOfWork;

class MasterDataDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $dir = dirname(__DIR__).'/data/';

        foreach (CsvImporter::read($dir.'item_categories.csv') as $row) {
            ItemCategory::updateOrCreate(['code' => $row['code']], $row);
        }

        foreach (CsvImporter::read($dir.'inspection_types.csv') as $row) {
            InspectionType::updateOrCreate(['code' => $row['code']], $row);
        }

        foreach (CsvImporter::read($dir.'inspection_parameters.csv') as $row) {
            InspectionParameter::updateOrCreate(['code' => $row['code']], $row);
        }

        foreach (CsvImporter::read($dir.'scopes_of_work.csv') as $row) {
            ScopeOfWork::updateOrCreate(['name' => $row['name']], $row);
        }

        foreach (CsvImporter::read($dir.'locations.csv') as $row) {
            Location::updateOrCreate(['name' => $row['name']], $row);
        }

        foreach (CsvImporter::read($dir.'customers.csv') as $row) {
            Customer::updateOrCreate(['name' => $row['name']], $row);
        }

        foreach (CsvImporter::read($dir.'owners.csv') as $row) {
            Owner::updateOrCreate(['name' => $row['name']], $row);
        }

        foreach (CsvImporter::read($dir.'allocations.csv') as $row) {
            Allocation::updateOrCreate(['name' => $row['name']], $row);
        }

        foreach (CsvImporter::read($dir.'conditions.csv') as $row) {
            Condition::updateOrCreate(['name' => $row['name']], $row);
        }

        $this->linkScopeParameters($dir.'scope_parameter.csv');
    }

    /**
     * Resolve scope/parameter names to ids and attach the many-to-many links.
     */
    private function linkScopeParameters(string $path): void
    {
        $scopes = ScopeOfWork::pluck('id', 'name');
        $parameters = InspectionParameter::pluck('id', 'name');

        $map = [];
        foreach (CsvImporter::read($path) as $row) {
            $scopeId = $scopes[$row['scope_name']] ?? null;
            $parameterId = $parameters[$row['parameter_name']] ?? null;

            if ($scopeId && $parameterId) {
                $map[$scopeId][] = $parameterId;
            }
        }

        foreach ($map as $scopeId => $parameterIds) {
            ScopeOfWork::find($scopeId)->parameters()->syncWithoutDetaching($parameterIds);
        }
    }
}
