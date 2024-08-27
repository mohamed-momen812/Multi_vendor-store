<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "description",
        "slug",
        "image",
        "category_id",
        "store_id",
        "price",
        "compare_price",
        "status",
    ];
    // applying global scopes
    // static allows the method to be used at the class level, ensuring that the logic defined in
    // booted is applied whenever the model class is loaded, without needing to create an instance of the model.
    protected static function booted(){
        static::addGlobalScope('store', new StoreScope());
    }

    // access this relations in the controller as Product::with($relations)
    // Define an inverse one-to-one in products to categories
    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    // Define an inverse one-to-one in products to stores
    public function store(){
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }


    public function tags () {
        return $this->belongsToMany(
            Tag::class,      // related model
            'product_tag',   // pivot table
            'product_id',    // FK in pivot table for the current model
            'tag_id',        // FK in pivot table for the related model
            'id',             // PK current model
            'id'             // PK related model
        );
    }

}
