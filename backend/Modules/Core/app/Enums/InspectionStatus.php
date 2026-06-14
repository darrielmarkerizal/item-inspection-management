<?php

namespace Modules\Core\Enums;

enum InspectionStatus: string
{
    case OPEN = 'open';
    case FOR_REVIEW = 'for_review';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match($this) {
            self::OPEN => 'Open',
            self::FOR_REVIEW => 'For Review',
            self::COMPLETED => 'Completed',
        };
    }

    public function isEditable(): bool
    {
        return $this === self::OPEN;
    }

    public function allowedTransitions(): array
    {
        return match($this) {
            self::OPEN => [self::FOR_REVIEW],
            self::FOR_REVIEW => [self::COMPLETED],
            self::COMPLETED => [],
        };
    }

    public function canTransitionTo(self $newStatus): bool
    {
        return in_array($newStatus, $this->allowedTransitions());
    }

    public static function options(): array
    {
        return array_map(
            fn (self $status): array => ['value' => $status->value, 'label' => $status->label()],
            self::cases()
        );
    }
}