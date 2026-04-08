<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(array $columns = ['*'])
    {
        return $this->model->newQuery()->select($columns)->get();
    }

    public function paginate(int $perPage = 15)
    {
        return $this->model->newQuery()->paginate($perPage);
    }

    public function find(int $id): ?Model
    {
        return $this->model->newQuery()->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->newQuery()->create($data);
    }

    public function update(int $id, array $data): ?Model
    {
        $model = $this->find($id);

        if (! $model) {
            return null;
        }

        $model->fill($data);
        $model->save();

        return $model;
    }

    public function delete(int $id): bool
    {
        $model = $this->find($id);

        if (! $model) {
            return false;
        }

        return (bool) $model->delete();
    }
}

