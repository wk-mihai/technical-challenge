<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * @param array $sort
     * @return Builder[]|Collection
     */
    public function all(array $with = [], array $sort = [])
    {
        return $this->make($with, $sort)->get();
    }

    /**
     * @param $records
     * @param array $with
     * @param array $sort
     * @return LengthAwarePaginator
     */
    public function paginate($records, array $with = [], array $sort = [])
    {
        return $this->make($with, $sort)->paginate($records);
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
     */
    public function store($data)
    {
        $record = new $this->model($data);

        $record->save();
    }

    /**
     * @param $record
     * @param array $data
     */
    public function update($record, array $data)
    {
        $record->fill($data);
        $record->save();
    }
}
