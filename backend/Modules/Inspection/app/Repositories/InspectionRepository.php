<?php

namespace Modules\Inspection\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Inspection\Models\Inspection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class InspectionRepository
{
    public function paginate(int $perPage): LengthAwarePaginator
    {
        return QueryBuilder::for(Inspection::class)
            ->with(['inspectionType', 'scopeOfWork', 'location', 'customer'])
            ->withCount('items')
            ->allowedFilters(
                AllowedFilter::exact('status'),
            )
            ->allowedSorts(
                'request_no',
                'date_submitted',
                'estimated_completion_date',
                'status',
                'related_to',
                'created_at',
            )
            ->defaultSort('-created_at')
            ->paginate($perPage)
            ->appends(request()->query());
    }

    public function statusCounts(): array
    {
        return Inspection::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }
}
