<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * GET /api/products
     * Returns a list of products with search and category filter
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by category_id
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Search by product name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $products = $query->get();

        return response()->json([
            'success' => true,
            'data' => $products,
            'meta' => [
                'total' => $products->count(),
                'filters' => [
                    'search' => $request->search,
                    'category_id' => $request->category_id,
                ],
            ],
        ]);
    }

    /**
     * POST /api/products
     * Creates a new product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
        ], [
            'name.required' => 'Product name is required.',
            'category_id.required' => 'Category ID is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be greater than 0.',
            'stock.required' => 'Stock quantity is required.',
            'stock.integer' => 'Stock must be an integer.',
            'stock.min' => 'Stock cannot be negative.',
        ]);

        $product = Product::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product->load('category'),
        ], 201);
    }

    /**
     * PUT /api/products/{id}
     * Updates a product
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'price' => 'sometimes|required|numeric|min:0.01',
            'stock' => 'sometimes|required|integer|min:0',
        ], [
            'name.required' => 'Product name is required.',
            'category_id.required' => 'Category ID is required.',
            'category_id.exists' => 'The selected category does not exist.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a number.',
            'price.min' => 'Price must be greater than 0.',
            'stock.required' => 'Stock quantity is required.',
            'stock.integer' => 'Stock must be an integer.',
            'stock.min' => 'Stock cannot be negative.',
        ]);

        $product->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product->load('category'),
        ]);
    }

    /**
     * DELETE /api/products/{id}
     * Deletes a product
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }
}
