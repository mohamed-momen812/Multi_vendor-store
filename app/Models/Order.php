<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "store_id",
        "user_id",
        "payment_method",
        "status",
        "payment_status"
    ];


    public function store(){
        return $this->belongsTo(Store::class);
    }

    public function user(){
        return $this->belongsTo(User::class)->withDefault([
            "name" => "Guest Customer",
        ]);
    }

    public function products(){
        return $this->belongsToMany(
            Product::class,
            "order_items",
            "order_id",
            "product_id",
            "id",
            "id"
        )
        ->using(OrderItem::class) // Specify the custom pivot model to use for the relationship.
        ->withPivot([
            'product_name', 'price', 'quantity', 'options'
        ]); // Set the columns on the pivot table to retrieve
    }

    public function address(){
        return $this->hasMany(OrderAdress::class); // billing and shipping
    }

    public function billingAddress(){
        return $this->hasOne(OrderAdress::class)
            ->where('type', 'billing'); // billing
    }

    public function shippigntAddress(){
        return $this->hasOne(OrderAdress::class)
            ->where('type', 'shipping'); // billing
    }



    protected static function booted(){
        // make listner for creating event without class
        static::creating(function (Order $order) {
            // 20240001, 20240002
            $order->number = Order::getNextOrderNumber();
        });
    }

    /**
     * get the next order number
     * @return float|int|string
     */
    public static function getNextOrderNumber() {

        $year = Carbon::now()->year;
        $number = Order::whereYear('created_at', $year)->max('number');
        if($number) {
            return $number + 1;
        }
        return $year . '0001';
    }
}
