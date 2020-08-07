<?php

namespace App\Repositories;

use App\Models\Type as Model;

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
}
