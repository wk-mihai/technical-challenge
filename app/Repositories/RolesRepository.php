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

    /**
     * @param $data
     */
    public function store($data)
    {
        $record = new $this->model($data);
        $record->save();

        array_map(function ($type) use ($record) {
            $record->roleTypes()->create(['type_id' => $type]);
        }, $data['types']);
    }

    /**
     * @param $record
     * @param array $data
     */
    public function update($record, array $data)
    {
        if ($record->isAdmin() && array_key_exists('slug', $data)) {
            unset($data['slug']);
        }

        $record->fill($data);
        $record->save();

        if (isset($data['types'])) {
            $existTypesIds = [];

            foreach ($data['types'] as $type) {
                $existTypesIds[] = $record->roleTypes()->firstOrCreate([
                    'type_id' => $type
                ])->id;
            }

            $record->roleTypes()
                ->whereNotIn('id', $existTypesIds)
                ->delete();
        }
    }
}
