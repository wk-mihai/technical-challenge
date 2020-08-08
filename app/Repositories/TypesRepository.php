<?php

namespace App\Repositories;

use App\Models\Type as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TypesRepository extends BaseRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return array
     */
    public function dropdown(): array
    {
        $records = $this->all([], ['name', 'asc'])
            ->pluck('name', 'id')
            ->toArray();

        return ['' => 'Select...'] + $records;
    }

    /**
     * @param Request $request
     * @param array $with
     * @param array $sort
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function allWhereHasTrainings(Request $request, array $with = [], array $sort = [])
    {
        return $this->make($with, $sort)
            ->whereHas('trainings', fn(Builder $query) => $query->search($request->input('search')))
            ->withCount(['trainings' => fn(Builder $query) => $query->search($request->input('search'))])
            ->get();
    }
}
