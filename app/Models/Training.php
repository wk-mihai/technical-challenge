<?php

namespace App\Models;

use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * @return BuildsQueries|Builder|mixed
     */
    public function scopeSearch(Builder $query, ?string $search = null)
    {
        return $query->when(
            !empty($search),
            fn(Builder $query) => $query->where(
                fn(Builder $query) => $query->where('name', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
            )
        );
    }

    /**
     * @return BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * @return HasMany
     */
    public function files()
    {
        return $this->hasMany(TrainingFile::class);
    }
}
