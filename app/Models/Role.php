<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'slug',
        'name'
    ];

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->slug === 'admin';
    }

    /**
     * @return HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return HasMany
     */
    public function roleTypes()
    {
        return $this->hasMany(RoleType::class);
    }

    /**
     * @return BelongsToMany
     */
    public function types()
    {
        return $this->belongsToMany(Type::class, 'role_types', 'role_id', 'type_id');
    }

    /**
     * @param bool $cut
     * @return string
     */
    public function humanizeTypeNames(bool $cut = false): string
    {
        $types = !$this->isAdmin() ? $this->types->pluck('name')->toArray() : [];
        $wasCut = false;

        if (count($types) > 3 && $cut) {
            $wasCut = true;
            $types = array_slice($types, 0, 3);
        }

        return implode(', ', $types) . ($cut && $wasCut ? '...' : '');
    }
}
