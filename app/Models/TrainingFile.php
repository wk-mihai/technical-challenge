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
}
