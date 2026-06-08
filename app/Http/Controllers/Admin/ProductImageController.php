<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use App\Services\ImageOptimizer;
use Illuminate\Http\JsonResponse;

class ProductImageController extends Controller
{
    public function store(ImageUploadRequest $request, ImageOptimizer $optimizer): JsonResponse
    {
        $path = $optimizer->storeOptimized($request->file('image'));

        return response()->json([
            'path' => $path,
            'url' => asset($path),
        ], 201);
    }
}
