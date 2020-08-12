<?php

namespace App\Repositories;

use App\Models\User as Model;

class UsersRepository extends BaseRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param $data
     */
    public function store($data)
    {
        $data['password'] = bcrypt($data['password']);

        $record = new $this->model($data);

        $record->save();
    }

    /**
     * @param $record
     * @param array $data
     */
    public function update($record, array $data)
    {
        if (array_key_exists('password', $data)) {
            if ($data['password'] === null) {
                unset($data['password']);
            } else {
                $data['password'] = bcrypt($data['password']);
            }
        }

        $record->fill($data);
        $record->save();
    }
}
