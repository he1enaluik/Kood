<?php

namespace App\Services;

use App\Data\Products;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ProductCatalogService
{
    private const STORAGE_PATH = 'tarukoda-admin-products.json';

    public function catalog(): array
    {
        $base = Products::all();
        $state = $this->readAdminState();

        $catalog = [];

        foreach ($base as $slug => $product) {
            if (in_array($slug, $state['deletedSlugs'], true)) {
                continue;
            }

            $catalog[$slug] = array_merge($product, $state['overrides'][$slug] ?? [], ['slug' => $slug]);
        }

        foreach ($state['overrides'] as $slug => $product) {
            if (in_array($slug, $state['deletedSlugs'], true) || isset($catalog[$slug])) {
                continue;
            }

            $catalog[$slug] = array_merge($product, ['slug' => $slug]);
        }

        return $catalog;
    }

    public function find(string $slug): ?array
    {
        return $this->catalog()[$slug] ?? null;
    }

    /**
     * @param  array{q?: string, category?: string, origin?: string, sort?: string, page?: int, per_page?: int}  $filters
     */
    public function search(array $filters = []): LengthAwarePaginator
    {
        $query = (string) ($filters['q'] ?? '');
        $category = (string) ($filters['category'] ?? 'all');
        $origin = (string) ($filters['origin'] ?? 'all');
        $sort = (string) ($filters['sort'] ?? 'default');
        $page = max(1, (int) ($filters['page'] ?? 1));
        $perPage = max(1, min(48, (int) ($filters['per_page'] ?? 12)));

        $items = collect($this->catalog())->values();

        if ($query !== '') {
            $needle = mb_strtolower($query);
            $items = $items->filter(function (array $product) use ($needle) {
                $haystack = mb_strtolower(implode(' ', [
                    $product['name'] ?? '',
                    $product['short_desc'] ?? '',
                    $product['origin'] ?? '',
                    $product['category'] ?? '',
                ]));

                return str_contains($haystack, $needle);
            });
        }

        if ($category !== '' && $category !== 'all') {
            $items = $items->filter(fn (array $product) => ($product['category'] ?? '') === $category);
        }

        if ($origin !== '' && $origin !== 'all') {
            $items = $items->filter(fn (array $product) => ($product['origin_filter'] ?? '') === $origin);
        }

        $items = $this->sortCollection($items, $sort);

        return $this->paginateCollection($items, $perPage, $page, $filters);
    }

    public function readAdminState(): array
    {
        if (!Storage::exists(self::STORAGE_PATH)) {
            return ['overrides' => [], 'deletedSlugs' => []];
        }

        $decoded = json_decode(Storage::get(self::STORAGE_PATH), true);

        if (!is_array($decoded)) {
            return ['overrides' => [], 'deletedSlugs' => []];
        }

        return [
            'overrides' => is_array($decoded['overrides'] ?? null) ? $decoded['overrides'] : [],
            'deletedSlugs' => is_array($decoded['deletedSlugs'] ?? null) ? $decoded['deletedSlugs'] : [],
        ];
    }

    public function writeAdminState(array $state): array
    {
        $normalized = [
            'overrides' => $state['overrides'] ?? [],
            'deletedSlugs' => array_values(array_unique($state['deletedSlugs'] ?? [])),
        ];

        Storage::put(
            self::STORAGE_PATH,
            json_encode($normalized, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        return $normalized;
    }

    private function sortCollection(Collection $items, string $sort): Collection
    {
        return match ($sort) {
            'price-asc' => $items->sortBy(fn (array $p) => (float) ($p['price'] ?? 0))->values(),
            'price-desc' => $items->sortByDesc(fn (array $p) => (float) ($p['price'] ?? 0))->values(),
            default => $items,
        };
    }

    private function paginateCollection(Collection $items, int $perPage, int $page, array $filters): LengthAwarePaginator
    {
        $total = $items->count();
        $slice = $items->forPage($page, $perPage)->values();

        return new Paginator(
            $slice,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => array_filter([
                    'q' => $filters['q'] ?? null,
                    'category' => ($filters['category'] ?? 'all') !== 'all' ? $filters['category'] : null,
                    'origin' => ($filters['origin'] ?? 'all') !== 'all' ? $filters['origin'] : null,
                    'sort' => ($filters['sort'] ?? 'default') !== 'default' ? $filters['sort'] : null,
                ]),
            ]
        );
    }
}
