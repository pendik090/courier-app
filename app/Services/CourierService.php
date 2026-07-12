<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class CourierService
{
    public function buildIndexQuery(Builder $query, array $filters): Builder
    {
        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (! empty($filters['level'])) {
            $levels = array_map('intval', explode(',', $filters['level']));
            $query->filterByLevel($levels);
        }

        if (isset($filters['sort']) && $filters['sort'] === 'latest') {
            $query->sortByLatest();
        } else {
            $query->orderBy('name', 'asc');
        }

        return $query;
    }

    public function paginate(Builder $query, int $perPage = 15): LengthAwarePaginator
    {
        return $query->paginate($perPage);
    }
}
