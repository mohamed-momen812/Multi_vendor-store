<?php

namespace App\Listeners;

use App\Models\Product;
use App\Repositories\Cart\CartRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class DeductProductQuantity
{
    public $cartRepository;
    /**
     * Create the event listener.
     */
    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        // reduce quantity of product by the quantity of each cart
        foreach ($this->cartRepository->get() as $item) {
            Product::where('id', $item->product_id)
                    ->update([
                        'quantity' => DB::raw("quantity - {$item->quantity}"), // DB::raw for create query in db without laravel excution
                    ]);
            };
    }
}
