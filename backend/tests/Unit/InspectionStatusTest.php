<?php

use Modules\Core\Enums\InspectionStatus;

it('allows only forward workflow transitions', function () {
    expect(InspectionStatus::OPEN->canTransitionTo(InspectionStatus::FOR_REVIEW))->toBeTrue();
    expect(InspectionStatus::FOR_REVIEW->canTransitionTo(InspectionStatus::COMPLETED))->toBeTrue();
    expect(InspectionStatus::OPEN->canTransitionTo(InspectionStatus::COMPLETED))->toBeFalse();
    expect(InspectionStatus::FOR_REVIEW->canTransitionTo(InspectionStatus::OPEN))->toBeFalse();
    expect(InspectionStatus::COMPLETED->canTransitionTo(InspectionStatus::OPEN))->toBeFalse();
});

it('is editable only when open', function () {
    expect(InspectionStatus::OPEN->isEditable())->toBeTrue();
    expect(InspectionStatus::FOR_REVIEW->isEditable())->toBeFalse();
    expect(InspectionStatus::COMPLETED->isEditable())->toBeFalse();
});

it('exposes value and label options for dropdowns', function () {
    $options = InspectionStatus::options();

    expect($options)->toHaveCount(3);
    expect($options[0])->toHaveKeys(['value', 'label']);
    expect($options[0]['value'])->toBe('open');
});
