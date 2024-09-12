<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(CartRepository $cart)
    {
        // $cart = $cart->get();
        return view("front.cart", compact("cart"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CartRepository $cartRepository)
    {
        $request->validate([
            "product_id"=> ["required","int","exists:products,id"],
            "quantity"=> ["nullable","int","min:1"],
        ]);

        $product = Product::findOrFail($request->post("product_id"));
        $cartRepository->add($product, $request->post("quantity"));

        return redirect()->route("cart.index")->with("success","Product added to cart!");
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartRepository $cartRepository, $id)
    {
        $request->validate([
            "quantity"=> ["require","int","min:1"],
        ]);

        $cartRepository->update($id, $request->post("quantity"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, CartRepository $cartRepository, Cart $cart)
    {
        $cartRepository->delete($cart);
    }
}
