<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItem extends Pivot //  OrderItem extends Pivot because OrderItem is pivot tabel, pivot extends model
{
    use HasFactory;

    protected $table = "order_items"; // because i don't name with laravel default so must till laravel the name ot tabel, laravel by default assume that the table name is orders not order_items


    public $timestamps = false;

    public $incrementing = true; // Indicates if the IDs are auto-incrementing , by default extends Pivot not auto increment but i use the id in order_item to access it from order tabel

    // no need to add fillable because by default in Pivot all fillabel

    public function product() {
        return $this->belongsTo(Product::class)->withDefault([
            "name" => $this->product->name,
        ]);
    }

    public function order() {
        return $this->belongsTo(Product::class);
    }
}

