<?php

namespace Modules\Inspection\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Enums\InspectionStatus;
use Modules\Core\Enums\ServiceType;
use Modules\Inspection\Models\Inspection;
use Modules\Inventory\Models\Item;
use Modules\MasterData\Models\Customer;
use Modules\MasterData\Models\InspectionType;
use Modules\MasterData\Models\Location;
use Modules\MasterData\Models\ScopeOfWork;

class InspectionDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // One inspection per workflow status so every listing tab has data.
        $this->makeInspection([
            'request_no' => 'REQ-2026-0001',
            'service_type' => ServiceType::NEW_ARRIVAL,
            'inspection_type' => 'Reg Prep',
            'scope' => 'New Arrival Full Inspection',
            'location' => 'Moomba',
            'customer' => 'PT Santosa',
            'related_to' => 'CO-02023-001',
            'dvc_code' => '1100049832',
            'status' => InspectionStatus::OPEN,
            'item_codes' => ['6203-00640-03-PQ-03-XAS-001', '6203-00640-03-PQ-03-XAS-036'],
            'charges' => [
                ['order_no' => 'CO-001', 'service_description' => 'SERV-001 Inspection', 'qty' => 5, 'unit_price' => 5, 'total' => 25],
            ],
        ]);

        $this->makeInspection([
            'request_no' => 'REQ-2026-0002',
            'service_type' => ServiceType::MAINTENANCE,
            'inspection_type' => 'Re-Inspection',
            'scope' => 'Maintenance Re-Inspection',
            'location' => 'Dampier',
            'customer' => 'Santos Ltd',
            'related_to' => 'CO-02023-014',
            'dvc_code' => '1100051120',
            'status' => InspectionStatus::FOR_REVIEW,
            'item_codes' => ['6205-00210-01-TB-02-XAS-014'],
            'charges' => [
                ['order_no' => 'CO-002', 'service_description' => 'SERV-002 Inspection', 'qty' => 2, 'unit_price' => 5, 'total' => 10],
            ],
        ]);

        $this->makeInspection([
            'request_no' => 'REQ-2026-0003',
            'service_type' => ServiceType::ON_SPOT,
            'inspection_type' => 'Cleaning & Drifting',
            'scope' => 'On Spot Drift Check',
            'location' => 'Karratha',
            'customer' => 'Woodside Energy',
            'related_to' => 'CO-02023-031',
            'dvc_code' => '1100052233',
            'status' => InspectionStatus::COMPLETED,
            'item_codes' => ['6214-00220-02-PJ-01-XAS-012'],
            'charges' => [],
        ]);
    }

    private function makeInspection(array $data): void
    {
        $inspection = Inspection::updateOrCreate(
            ['request_no' => $data['request_no']],
            [
                'service_type' => $data['service_type'],
                'inspection_type_id' => InspectionType::where('name', $data['inspection_type'])->value('id'),
                'scope_of_work_id' => ScopeOfWork::where('name', $data['scope'])->value('id'),
                'location_id' => Location::where('name', $data['location'])->value('id'),
                'customer_id' => Customer::where('name', $data['customer'])->value('id'),
                'related_to' => $data['related_to'],
                'dvc_code' => $data['dvc_code'],
                'date_submitted' => '2026-05-01',
                'estimated_completion_date' => '2026-05-05',
                'status' => $data['status'],
                'charge_to_customer' => ! empty($data['charges']),
            ]
        );

        // Rebuild children so the seeder stays idempotent.
        $inspection->items()->delete();
        $inspection->charges()->delete();
        $inspection->statusHistories()->delete();

        foreach ($data['item_codes'] as $code) {
            $item = Item::with(['lots.allocation', 'lots.owner', 'lots.condition'])
                ->where('code', $code)
                ->first();

            if (! $item) {
                continue;
            }

            $inspectionItem = $inspection->items()->create([
                'item_id' => $item->id,
                'item_description' => $item->description,
                'qty_required' => 3,
                'inspection_required' => true,
            ]);

            // Snapshot each lot onto the inspection item.
            foreach ($item->lots as $lot) {
                $inspectionItem->lots()->create([
                    'item_lot_id' => $lot->id,
                    'lot_no' => $lot->lot_no,
                    'allocation' => $lot->allocation?->name,
                    'owner' => $lot->owner?->name,
                    'condition' => $lot->condition?->name,
                    'qty' => 3,
                ]);
            }
        }

        foreach ($data['charges'] as $charge) {
            $inspection->charges()->create($charge);
        }

        $this->seedStatusHistory($inspection, $data['status']);
    }

    private function seedStatusHistory(Inspection $inspection, InspectionStatus $current): void
    {
        $flow = [InspectionStatus::OPEN, InspectionStatus::FOR_REVIEW, InspectionStatus::COMPLETED];
        $previous = null;

        foreach ($flow as $status) {
            $inspection->statusHistories()->create([
                'from_status' => $previous,
                'to_status' => $status,
                'changed_at' => now(),
            ]);

            if ($status === $current) {
                break;
            }

            $previous = $status;
        }
    }
}
