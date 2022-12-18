<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        // $productVariants = ProductVariant::with('variants')->get()->unique('variant')->values();
        return view('products.index', [
            'products' => Product::with("product_variant_prices")->paginate(5),
            'productsVariants' => ProductVariant::with('variants')->get()->unique('variant')->values()
        ]);
    }


    public function filter(Request $request)
    {
        $title = $request->title;
        $min_price = $request->price_from;
        $max_price = $request->price_to;
        $date = $request->date;

        $products = Product::where('title', 'like', "%" . $request->title . "%")
            ->orWhereDate('created_at', '=', $request->date)
            ->orWhereHas('product_variant_prices', function ($query) use ($request) {
                $query->whereBetween('price', [$request->price_from, $request->price_to]);
            })
            ->paginate(10);

        $productsVariants = ProductVariant::with('variants')->get()->unique('variant')->values();
        return view('products.index', compact('products', 'productsVariants'));
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
