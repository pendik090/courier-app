<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
        ];
    }

    public function scopeSearch(Builder $query, string $terms): Builder
    {
        collect(explode(' ', $terms))
            ->filter()
            ->each(function (string $term) use ($query) {
                $query->where(function (Builder $q) use ($term) {
                    $q->where('name', 'like', "%{$term}%");
                });
            });

        return $query;
    }

    public function scopeFilterByLevel(Builder $query, array $levels): Builder
    {
        return $query->whereIn('level', $levels);
    }

    public function scopeSortByLatest(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }
}
