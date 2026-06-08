<?php

namespace Tests\Unit;

use App\Services\ProductCatalogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_filters_by_category_and_query(): void
    {
        $service = app(ProductCatalogService::class);

        $results = $service->search([
            'q' => 'Niidu',
            'category' => 'mesi',
            'per_page' => 12,
        ]);

        $this->assertGreaterThanOrEqual(1, $results->total());
        $this->assertSame('niidumesi', $results->items()[0]['slug']);
    }

    public function test_catalog_excludes_deleted_products(): void
    {
        $service = app(ProductCatalogService::class);
        $service->writeAdminState([
            'overrides' => [],
            'deletedSlugs' => ['niidumesi'],
        ]);

        $this->assertNull($service->find('niidumesi'));
    }
}
