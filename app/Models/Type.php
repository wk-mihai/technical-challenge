<?php

namespace App\Models;

use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Type extends Model
{
    protected $fillable = [
        'slug',
        'name'
    ];

    /**
     * @param Builder $query
     * @return BuildsQueries|Builder|mixed
     */
    public function scopeHasAccess(Builder $query)
    {
        $userRole = auth()->user()->role;

        return $query->when(
            isset($userRole) && !$userRole->isAdmin(),
            fn(Builder $query) => $query->whereHas(
                'roles',
                fn(Builder $query) => $query->where('roles.id', $userRole->id)
            )
        );
    }

    /**
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_types', 'type_id', 'role_id');
    }

    /**
     * @return HasMany
     */
    public function roleTypes()
    {
        return $this->hasMany(RoleType::class);
    }

    /**
     * @return HasMany
     */
    public function trainings()
    {
        return $this->hasMany(Training::class);
    }
}
