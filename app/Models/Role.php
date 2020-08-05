<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'slug',
        'name',
        'can_view_trainings',
    ];

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->slug === 'admin';
    }
}
