<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ProductCatalogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductCatalogService $catalog) {}

    public function index(Request $request): JsonResponse
    {
        $paginator = $this->catalog->search($request->only([
            'q', 'category', 'origin', 'sort', 'page', 'per_page',
        ]));

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $product = $this->catalog->find($slug);

        if (!$product) {
            return response()->json(['message' => 'Toodet ei leitud.'], 404);
        }

        return response()->json(['data' => $product]);
    }

}
