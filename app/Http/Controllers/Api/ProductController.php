<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Response;

class ProductController extends Controller implements HasMiddleware
{

    // using middleware inside controller must implements HasMiddleware
     /**
     * @inheritDoc
     */
    public static function middleware() {
        return [ // should be authenticated to access those routes
            new Middleware('auth:sanctum', except: ['index', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products =  Product::filter($request->query())
            ->with('category:id,name', 'store:id,name', 'tags:id,name') // access spicefic fields in relations
            ->paginate(10);

        // create an array form the given collection
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'status' => 'in:active,inactive',
            'price'=> 'required|numeric|min:0',
            'compare_price'=> 'required|numeric|gt:price',
        ]);

        $product = Product::create($request->all());

        return Response::json($product, 201, [
            'my-header' => 'momen header'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // resource used to transforme collection to array
        return new ProductResource($product);

        // Eager load relations on the model, use with with query builder
        // return $product->load('category:id,name', 'store:id,name', 'tags:id,name');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        // sometimes mean if request have the field its require if not its not require
         $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'status' => 'in:active,inactive',
            'price'=> 'sometimes|required|numeric|min:0',
            'compare_price'=> 'sometimes|required|numeric|gt:price',
        ]);

        // any field in request will apdate and the fields not passed throw request will not change
        $product->update($request->all());

        return Response::json($product);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Product::destroy( $id );

        return Response::json([
            'status' => 'success',
            'message' => 'Product deleted successfully'
        ], 200);
    }


}
