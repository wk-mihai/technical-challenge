<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUsers extends Model
{
    protected $fillable = [
        'user_id',
        'role_id'
    ];
}
