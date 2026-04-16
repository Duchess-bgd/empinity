<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->get();
        $categories = Category::all();

        return Inertia::render('Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'search' => $request->search,
                'category_id' => $request->category_id,
            ],
        ]);
    }


    public function create()
    {
        return Inertia::render('Products/Form', [
            'product' => null,
            'categories' => Category::all(),
        ]);
    }


    public function edit($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return Inertia::render('Products/Form', [
            'product' => $product,
            'categories' => Category::all(),
        ]);
    }
}
