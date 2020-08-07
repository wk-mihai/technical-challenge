<?php

namespace App\Repositories;

use App\Models\Role as Model;

class RolesRepository extends BaseRepository
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
