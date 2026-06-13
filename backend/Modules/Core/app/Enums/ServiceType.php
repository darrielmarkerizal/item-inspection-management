<?php

namespace Modules\Core\Enums;

enum ServiceType: string
{
    case NEW_ARRIVAL = 'new_arrival';
    case MAINTENANCE = 'maintenance';
    case ON_SPOT = 'on_spot';

    public function label(): string
    {
        return match ($this) {
            self::NEW_ARRIVAL => 'New Arrival',
            self::MAINTENANCE => 'Maintenance',
            self::ON_SPOT => 'On Spot',
        };
    }

    /**
     * Dropdown options for the prefetched master-data endpoint.
     *
     * @return array<int, array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $type): array => ['value' => $type->value, 'label' => $type->label()],
            self::cases()
        );
    }
}
