<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingFile extends Model
{
    protected $fillable = [
        'training_id',
        'name',
        'type',
        'url'
    ];

    /**
     * @return string
     */
    public function getFullFileUrlAttribute(): string
    {
        return route('training.files', [
            'id'     => $this->training_id,
            'fileId' => $this->id
        ]);
    }
}
