<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;


class CartModelRepository implements CartRepository
{
    /**
     * @inheritDoc
     */
    public function get() {
        return Cart::with("product")
            ->where("user_id", Auth::id())
            // ->where("cookie_id", $this->getCookieId()) form global scope
            ->get();
    }

    /**
     * add product to cart if not exist in cart and modify qauntity if exist
     * @inheritDoc
     */
    public function add(Product $product, $quantity = 1) {

        $item = Cart::where("product_id", $product->id)
            // ->where("cookie_id", $this->getCookieId()) form global scope
            ->first();

        if(!$item) {
            return Cart::create([
                // "id" => no need to use id because i use observe for this
                // "cookie_id"=> from event (ovserver) , creating
                "product_id" => $product->id,
                "user_id" => Auth::id(),
                "quantity" => $quantity
             ]);
        }
        return $item->increment("quantity", $quantity);

    }

    /**
     * @inheritDoc
     */
    public function update( $id, $quantity) {
        Cart::where("id", $id)
           // ->where("cookie_id", $this->getCookieId()) form global scope
            ->update([
                "quantity" => $quantity
            ]);
    }

    /**
     * @inheritDoc
     */
    public function delete(Cart $cart) {
        return Cart::where("id", $cart->id)
            // ->where("cookie_id", $this->getCookieId()) form global scope
            ->delete();
    }

    /**
     * @inheritDoc
     */
    public function empty() {
        return Cart::query()
            // ->where("cookie_id", $this->getCookieId()) form global scope
            ->delete();
    }


    /**
     * @inheritDoc
     */
    public function total() {
        return (float) Cart::join("products","products.id","=","carts.product_id")
           ->selectRaw("SUM(products.price * carts.quantity) as total")
           ->value("total"); //just return total
    }

}
