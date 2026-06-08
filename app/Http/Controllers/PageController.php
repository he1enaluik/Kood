<?php

namespace App\Http\Controllers;

use App\Data\Products;
use App\Services\ProductCatalogService;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct(private readonly ProductCatalogService $catalog) {}

    public function home()
    {
        return view('pages.home');
    }

    public function products(Request $request)
    {
        $filters = $request->only(['q', 'category', 'origin', 'sort', 'page']);
        $paginator = $this->catalog->search($filters);

        return view('pages.products', [
            'products' => $paginator,
            'filters' => array_merge([
                'q' => '',
                'category' => 'all',
                'origin' => 'all',
                'sort' => 'default',
            ], $filters),
        ]);
    }

    public function productShow(string $slug)
    {
        $product = $this->catalog->find($slug);

        if (!$product) {
            abort(404);
        }

        return view('pages.product-show', compact('product'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function order()
    {
        return view('pages.order');
    }

    public function orderSuccess()
    {
        return view('pages.order-success');
    }
}
