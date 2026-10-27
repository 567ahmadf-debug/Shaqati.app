<?php

namespace App\Services\Properties;

use App\Models\Property;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListPropertiesService
{
    public function execute(
        array $filters = []
    ): LengthAwarePaginator {
        $query = Property::query()
            ->approved()
            ->search($filters['search'] ?? null);

        if (!empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        if (!empty($filters['property_type'])) {
            $query->where(
                'property_type',
                $filters['property_type']
            );
        }

        if (!empty($filters['availability_status'])) {
            $query->where(
                'availability_status',
                $filters['availability_status']
            );
        }

        if (
            isset($filters['min_price'])
            && $filters['min_price'] !== ''
        ) {
            $query->where(
                'rental_price',
                '>=',
                $filters['min_price']
            );
        }

        if (
            isset($filters['max_price'])
            && $filters['max_price'] !== ''
        ) {
            $query->where(
                'rental_price',
                '<=',
                $filters['max_price']
            );
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $perPage = (int) ($filters['per_page'] ?? 20);

        $query->orderBy($sortBy, $sortDirection);

        // ترتيب إضافي ثابت عند تساوي قيمة الترتيب الأساسية.
        if ($sortBy !== 'id') {
            $query->orderByDesc('id');
        }

        return $query
            ->paginate($perPage)
            ->withQueryString();
    }
}
