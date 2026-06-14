<?php

namespace Modules\MasterData\Services;

use Illuminate\Support\Facades\DB;
use Modules\MasterData\Models\ScopeOfWork;

class ScopeOfWorkService
{
    public function create(array $data): ScopeOfWork
    {
        return DB::transaction(function () use ($data) {
            $scope = ScopeOfWork::create([
                'name' => $data['name'],
                'service_type' => $data['service_type'],
                'description' => $data['description'] ?? null,
            ]);

            $scope->parameters()->sync($data['parameter_ids'] ?? []);

            return $scope->load('parameters');
        });
    }
}
