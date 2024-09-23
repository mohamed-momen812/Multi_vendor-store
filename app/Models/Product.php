<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    // The attributes that should be hidden for serialization.
    protected $hidden = [
        "created_at",
        'updated_at',
        'deleted_at',
        'image'
    ];

    // The accessors to append to the model's array response.
    protected $appends = [
        'image_url', // accessor
    ];

    // applying global scopes
    // static allows the method to be used at the class level, ensuring that the logic defined in
    // booted is applied whenever the model class is loaded, without needing to create an instance of the model.
    protected static function booted(){ // The booted method is used to run any logic when a model is initialized.
        static::addGlobalScope('store', new StoreScope());

        // creating slug before saving the product
        static::creating(function (Product $product) {
            $product->slug = Str::slug($product->name);
        });

        // self:: is bound to the class where the method or property is defined.
        //static:: allows for late static binding and refers to the class that actually calls the method, even if it's inherited from a parent class.
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


    public function tags () { // many to many relationship
        return $this->belongsToMany( // this refer to the product
            Tag::class,      // related model
            'product_tag',   // pivot table
            'product_id',    // FK in pivot table for the current model
            'tag_id',        // FK in pivot table for the related model
            'id',             // PK current model
            'id'             // PK related model
        );
    }

    public function scopeActive( Builder $builder ) {
        $builder->where("status", "=", "active");
    }


    // Accessors: when calling coulmn that is not in the table, laravel will search for the accessrs
    // if it find accessor mathch the name it will excute it
    // Accessor must match  get .... Attribute and the name of the accessor in the calmel case start with calpital letter
    // calling the accessor must be in snack case

    // Accessor to get the image
    public function getImageUrlAttribute() {
        // if no image use default url to get image not found
        if(!$this->image) {
            return 'https://www.incathlab.com/images/products/default_product.png';
        }
        // if product have an image
        if(Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        // else generate the full pass to the image in the store folder
        return asset('storage/' . $this->image);
    }

    // Accessor to get the salary percent
    public function getSalePercentAttribute() {

        if(!$this->compare_price) {
            return 0;
        }

        return round(100 - (100 * $this->price / $this->compare_price), 1);
    }


    public function scopeFilter(Builder $builder, $filters) {
        $options = array_merge([
            'store_id' => null,
            'category_id' => null,
            'tag_id' => null,
            'status' => 'active',
        ], $filters);


        // Apply the callback if the given "value" is (or resolves to) truthy.
        $builder->when($options['store_id'], function ($builder, $value)  {
            $builder->where('store_id','=', $value);
        });

        $builder->when($options['status'], function ($builder, $value)  {
            $builder->where('status','=', $value);
        });

        $builder->when($options['category_id'], function ($builder, $value)  {
            $builder->where('category_id','=', $value);
        });

        $builder->when($options['tag_id'], function ($builder, $value)  {
            $builder->whereRaw('id IN (SELECT product_id FROM product_tag WHERE tag_id = ?)', [$value]);
        });

    }


}
