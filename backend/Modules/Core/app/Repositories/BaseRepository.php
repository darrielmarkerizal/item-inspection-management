<?php

namespace Modules\Core\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

abstract class BaseRepository
{
    protected Model $model;

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function findById(int $id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(Model $model, array $data): Model
    {
        $model->update($data);
        return $model->fresh();
    }

    public function delete(Model $model): bool
    {
        return $model->delete();
    }
}