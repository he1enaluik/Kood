<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProductStateRequest;
use App\Services\ProductCatalogService;

class ProductCatalogController extends Controller
{
    public function __construct(private readonly ProductCatalogService $catalog) {}

    public function show()
    {
        return response()->json($this->catalog->readAdminState());
    }

    public function update(AdminProductStateRequest $request)
    {
        $validated = $request->validated();

        $overrides = [];
        foreach ($validated['overrides'] as $product) {
            $overrides[$product['slug']] = $product;
        }

        $state = $this->catalog->writeAdminState([
            'overrides' => $overrides,
            'deletedSlugs' => $validated['deletedSlugs'],
        ]);

        return response()->json($state);
    }
}
