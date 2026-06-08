<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProductStateRequest;
use App\Http\Requests\ImageUploadRequest;
use App\Services\ImageOptimizer;
use App\Services\ProductCatalogService;
use Illuminate\Http\JsonResponse;

class ProductCatalogApiController extends Controller
{
    public function __construct(private readonly ProductCatalogService $catalog) {}

    public function show(): JsonResponse
    {
        return response()->json($this->catalog->readAdminState());
    }

    public function update(AdminProductStateRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $overrides = [];
        foreach ($validated['overrides'] as $product) {
            $slug = $product['slug'];
            $overrides[$slug] = $product;
        }

        $state = $this->catalog->writeAdminState([
            'overrides' => $overrides,
            'deletedSlugs' => $validated['deletedSlugs'],
        ]);

        return response()->json($state);
    }

    public function uploadImage(ImageUploadRequest $request, ImageOptimizer $optimizer): JsonResponse
    {
        $path = $optimizer->storeOptimized($request->file('image'));

        return response()->json([
            'path' => $path,
            'url' => asset($path),
        ], 201);
    }
}
