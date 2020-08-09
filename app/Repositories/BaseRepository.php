<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

abstract class BaseRepository
{
    /** @var Model */
    protected Model $model;

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param array $with
     * @param array $sort
     * @return Builder
     */
    public function make(array $with = [], array $sort = [])
    {
        $query = $this->model->with($with);

        if (count($sort) === 2) {
            $query->orderBy($sort[0], $sort[1]);
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query;
    }

    /**
     * @param array $with
     * @return Builder|Model|object|null
     */
    public function first(array $with = [])
    {
        return $this->make($with)->first();
    }

    /**
     * @param array $with
     * @param array $sort
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function all(array $with = [], array $sort = [])
    {
        return $this->make($with, $sort)->get();
    }

    /**
     * @param $records
     * @param array $with
     * @param array $sort
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($records, array $with = [], array $sort = [])
    {
        return $this->make($with, $sort)->paginate($records);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param $data
     * @throws ValidationException
     */
    public function store($data)
    {
        $record = new $this->model($data);

        if (!$record->save()) {
            throw new ValidationException($record->getErrors());
        }
    }

    /**
     * @param $record
     * @param array $data
     * @throws ValidationException
     */
    public function update($record, array $data)
    {
        $record->fill($data);

        if (!$record->save()) {
            throw new ValidationException($record->getErrors());
        }
    }
}
