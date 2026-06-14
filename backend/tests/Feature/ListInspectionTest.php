<?php

beforeEach(function () {
    createInspectionWithStatus('open');
    createInspectionWithStatus('for_review');
    createInspectionWithStatus('completed');
});

it('lists all inspections with status counts', function () {
    $this->getJson('/api/v1/inspections')
        ->assertOk()
        ->assertJsonPath('data.pagination.total', 3)
        ->assertJsonPath('data.status_counts.open', 1)
        ->assertJsonPath('data.status_counts.for_review', 1)
        ->assertJsonPath('data.status_counts.completed', 1);
});

it('filters inspections by status', function () {
    $this->getJson('/api/v1/inspections?filter[status]=open')
        ->assertOk()
        ->assertJsonPath('data.pagination.total', 1)
        ->assertJsonPath('data.items.0.status.value', 'open');
});

it('sorts inspections by request number ascending and descending', function () {
    $asc = $this->getJson('/api/v1/inspections?sort=request_no')->json('data.items.0.request_no');
    $desc = $this->getJson('/api/v1/inspections?sort=-request_no')->json('data.items.0.request_no');

    expect($asc)->toBeLessThan($desc);
});

it('rejects an invalid status filter value', function () {
    $this->getJson('/api/v1/inspections?filter[status]=bogus')->assertStatus(422);
});

it('rejects a disallowed filter', function () {
    $this->getJson('/api/v1/inspections?filter[unknown]=x')->assertStatus(400);
});

it('rejects a disallowed sort', function () {
    $this->getJson('/api/v1/inspections?sort=secret')->assertStatus(400);
});
