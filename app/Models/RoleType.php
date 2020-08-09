<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleType extends Model
{
    protected $fillable = [
        'role_id',
        'type_id',
    ];
}
