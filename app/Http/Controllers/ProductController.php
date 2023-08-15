<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function show(Request $request, string $id): JsonResponse
    {
        return response()->json(Product::find($id)->toArray(), 200);
    }

    public function store(Request $request): JsonResponse
    {
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->restaurant_id = $request->restaurant_id;
        $product->save();

        return response()->json(Product::find($product->id)->toArray(), 201);
    }
    public function update(Request $request, string $id): JsonResponse
    {
        $product = Product::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();

        return response()->json(Product::find($product->id)->toArray(), 200);
    }

    public function destroy(Request $request, string $id)
    {
        $product = Product::find($id);
        $product->delete();
        return response()->json(null, 204);
    }
}
