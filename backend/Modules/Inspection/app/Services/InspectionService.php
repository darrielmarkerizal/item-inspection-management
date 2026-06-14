<?php

namespace Modules\Inspection\Services;

use Modules\Core\Enums\InspectionStatus;
use Modules\Inspection\Http\Resources\InspectionListResource;
use Modules\Inspection\Repositories\InspectionRepository;

class InspectionService
{
    public function __construct(private readonly InspectionRepository $repository) {}

    public function list(array $filters): array
    {
        $paginator = $this->repository->paginate($filters['per_page'] ?? 15);

        return [
            'items' => InspectionListResource::collection($paginator->getCollection()),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'status_counts' => $this->normalizeCounts($this->repository->statusCounts()),
        ];
    }

    private function normalizeCounts(array $counts): array
    {
        $result = [];

        foreach (InspectionStatus::cases() as $status) {
            $result[$status->value] = (int) ($counts[$status->value] ?? 0);
        }

        return $result;
    }
}
