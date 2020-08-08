<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'type_id',
        'name',
        'content'
    ];

    /**
     * @param Builder $query
     * @param string|null $search
     */
    public function scopeSearch(Builder $query, ?string $search = null)
    {
        $query->when(
            !empty($search),
            fn(Builder $query) => $query->where(
                fn(Builder $query) => $query->where('name', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
            )
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(TrainingFile::class);
    }
}
