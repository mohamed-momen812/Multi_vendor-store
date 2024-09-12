<?php

namespace App\Models;

use App\Observers\CartObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;


class Cart extends Model
{
    use HasFactory;

    public $incrementing = false; // we use uuid as primary so must tell laravel to prevent auto increament

    protected $fillable = [
        // "id"  not fillabel because i will generate it, fillabel to get from user
        "cookie_id",
        "user_id",
        "product_id",
        "quantity",
        "options"
    ];


    protected static function booted()
    {
        // use event and listner to create cart id and cart cookie id
        static::observe(CartObserver::class);

        // add global scope to make query as where("cookie_id", Cart::getCookieId())
        static::addGlobalScope("cookie_id", function (Builder $builder) {
            $builder->where("cookie_id", Cart::getCookieId());
        });

    }

    /**
     * get cookie id if existe and create and send via queue if not exist
     * @return array|string|\Ramsey\Uuid\UuidInterface
     */
    public static function getCookieId() {

        $cookie_id = Cookie::get("cart_id");

        if (!$cookie_id) {
            $cookie_id = Str::uuid();
            Cookie::queue("cart_id", $cookie_id, 30*24*60);
        }

        return $cookie_id;
    }

    /**
     * relatrion of cart to user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo(User::class)->withDefault([
            "name"=> "Anonymous",
        ]);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }


}
