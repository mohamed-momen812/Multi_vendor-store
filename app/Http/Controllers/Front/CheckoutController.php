<?php

namespace App\Http\Controllers\Front;

use App\Events\OrderEvent\OrderCreated;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Intl\Countries;
use Throwable;

class CheckoutController extends Controller
{
    public function create(CartRepository $cart) {

        $countries = Countries::getNames(); // get countries from symfonie package

        if($cart->get()->count() === 0) {
            return redirect()->route('home');
        }
        return view("front.checkout",  compact('cart', 'countries'));
    }

    public function store(Request $request, CartRepository $cart) {
        // validation for multi dimentional array
        $request->validate([
            'addr.billing.first_name'=> ['required','string','max:255'],
            'addr.billing.last_name'=> ['required','string','max:255'],
            'addr.billing.email'=> ['required','string','max:255'],
            'addr.billing.phone_number'=> ['required','string','max:255'],
            'addr.billing.city'=> ['required','string','max:255'],
        ]);

        // each cart has  1 product and 1 store, group carts by store_id here each store have many carts
        // make order for each store collection of carts, because multi store, if in single store no need for all of this
        $items = $cart->get()->groupBy('product.store_id')->all(); // all to get the array from collection

        // items ( [store_id => cart_items])
        DB::beginTransaction();
            try {
                foreach($items as $store_id => $cart_items){
                    // create Order
                    $order = Order::create([
                        'store_id' => $store_id,
                        'user_id' => Auth::id(),
                        'payment_method' => 'cod',
                    ]);

                    // create OrderItem for each cart
                    foreach ($cart_items as $item) {
                        OrderItem::create([ // each cart has order item
                            'order_id' => $order->id, // from above order
                            'product_id' => $item->product_id,
                            'quantity'=> $item->quantity,
                            'product_name'=> $item->product->name,
                            'price'=> $item->product->price,
                        ]);
                    }

                    // create Orderaddress, billing and shipping
                    foreach ($request->post('addr') as $type => $address) {
                        $address['type'] = $type;

                        // reate a New Related Model: By calling create on the relationship, you are effectively creating a new OrderAddress model and associating it with the Order model.
                        // The create method is a shorthand for creating a new model instance and saving it to the database in one ste
                        $order->address()->create($address); //  $order->address() return relation with its query builder so can use create
                    }
                }

                DB::commit(); // must use to commit the transaction to data base

                event(new OrderCreated( $order));

            } catch (Throwable $th) {
                DB::rollBack(); // transaction failed
                throw $th;
            }

        // return redirect()->route('home'); comment for develop mode

      }
    }

