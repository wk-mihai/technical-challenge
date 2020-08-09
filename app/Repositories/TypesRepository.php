<?php

namespace App\Repositories;

use App\Models\Type as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TypesRepository extends BaseRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param bool $usePlaceholder
     * @return array
     */
    public function dropdown(bool $usePlaceholder = true): array
    {
        $records = $this->all([], ['name', 'asc'])
            ->pluck('name', 'id')
            ->toArray();

        return $usePlaceholder ? ['' => 'Select...'] + $records : $records;
    }

    /**
     * @param Request $request
     * @param array $with
     * @param array $sort
     * @return Builder[]|Collection
     */
    public function allWhereHasTrainings(Request $request, array $with = [], array $sort = [])
    {
        return $this->make($with, $sort)
            ->hasAccess()
            ->whereHas('trainings', fn(Builder $query) => $query->search($request->input('search')))
            ->withCount(['trainings' => fn(Builder $query) => $query->search($request->input('search'))])
            ->get();
    }

    /**
     * @param string $slug
     * @return mixed
     */
    public function getTypeWithAccess(string $slug)
    {
        return $this->model
            ->hasAccess()
            ->firstWhere('slug', $slug);
    }
}
