<?php

namespace Modules\Inspection\Services;

use Illuminate\Support\Facades\DB;
use Modules\Core\Enums\InspectionStatus;
use Modules\Core\Exceptions\InspectionNotEditableException;
use Modules\Core\Exceptions\InvalidStatusTransitionException;
use Modules\Inspection\Http\Resources\InspectionListResource;
use Modules\Inspection\Models\Inspection;
use Modules\Inspection\Repositories\InspectionRepository;
use Modules\Inventory\Models\Item;
use Modules\Inventory\Models\ItemLot;

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

    public function find(int|string $id): Inspection
    {
        return $this->repository->find($id);
    }

    public function create(array $data): Inspection
    {
        return DB::transaction(function () use ($data) {
            $inspection = $this->repository->create(array_merge(
                $this->headerAttributes($data),
                [
                    'request_no' => $this->generateRequestNo(),
                    'status' => InspectionStatus::OPEN,
                ]
            ));

            $this->syncItems($inspection, $data['items']);

            $inspection->statusHistories()->create([
                'from_status' => null,
                'to_status' => InspectionStatus::OPEN,
                'changed_at' => now(),
            ]);

            return $this->repository->find($inspection->id);
        });
    }

    public function update(int|string $id, array $data): Inspection
    {
        return DB::transaction(function () use ($id, $data) {
            $inspection = $this->repository->find($id);

            if (! $inspection->status->isEditable()) {
                throw new InspectionNotEditableException;
            }

            $this->repository->update($inspection, $this->headerAttributes($data));

            foreach ($inspection->items as $item) {
                $item->lots()->delete();
            }
            $inspection->items()->delete();

            $this->syncItems($inspection, $data['items']);

            return $this->repository->find($id);
        });
    }

    public function updateStatus(int|string $id, string $target): Inspection
    {
        return DB::transaction(function () use ($id, $target) {
            $inspection = $this->repository->find($id);
            $current = $inspection->status;
            $targetStatus = InspectionStatus::from($target);

            if (! $current->canTransitionTo($targetStatus)) {
                throw new InvalidStatusTransitionException($current->value, $targetStatus->value);
            }

            $this->repository->update($inspection, ['status' => $targetStatus]);

            $inspection->statusHistories()->create([
                'from_status' => $current,
                'to_status' => $targetStatus,
                'changed_at' => now(),
            ]);

            return $this->repository->find($id);
        });
    }

    private function syncItems(Inspection $inspection, array $items): void
    {
        foreach ($items as $itemData) {
            $item = Item::find($itemData['item_id']);

            $inspectionItem = $inspection->items()->create([
                'item_id' => $item?->id,
                'item_description' => $item?->description,
            ]);

            foreach ($itemData['lots'] as $lotData) {
                $lot = ItemLot::with(['allocation', 'owner', 'condition'])->find($lotData['item_lot_id']);

                $inspectionItem->lots()->create([
                    'item_lot_id' => $lot?->id,
                    'lot_no' => $lot?->lot_no,
                    'allocation' => $lot?->allocation?->name,
                    'owner' => $lot?->owner?->name,
                    'condition' => $lot?->condition?->name,
                    'qty_required' => $lotData['qty_required'],
                    'inspection_required' => $lotData['inspection_required'] ?? true,
                ]);
            }
        }
    }

    private function headerAttributes(array $data): array
    {
        return [
            'service_type' => $data['service_type'],
            'inspection_type_id' => $data['inspection_type_id'] ?? null,
            'scope_of_work_id' => $data['scope_of_work_id'] ?? null,
            'location_id' => $data['location_id'] ?? null,
            'customer_id' => $data['customer_id'] ?? null,
            'related_to' => $data['related_to'] ?? null,
            'dvc_code' => $data['dvc_code'] ?? null,
            'date_submitted' => $data['date_submitted'] ?? null,
            'estimated_completion_date' => $data['estimated_completion_date'] ?? null,
            'note_to_yard' => $data['note_to_yard'] ?? null,
            'charge_to_customer' => $data['charge_to_customer'] ?? false,
        ];
    }

    private function generateRequestNo(): string
    {
        $prefix = 'REQ-'.now()->year.'-';

        $last = Inspection::where('request_no', 'like', $prefix.'%')
            ->orderByDesc('request_no')
            ->value('request_no');

        $sequence = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix.str_pad((string) $sequence, 4, '0', STR_PAD_LEFT);
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
