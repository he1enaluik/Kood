<?php

namespace App\Http\Controllers;

use App\Data\Products;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function products()
    {
        return view('pages.products', [
            'products' => Products::all(),
        ]);
    }

    public function productShow(string $slug)
    {
        $product = Products::find($slug);

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

    public function legal()
    {
        return view('pages.legal');
    }
}
