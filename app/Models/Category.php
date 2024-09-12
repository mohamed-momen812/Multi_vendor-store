<?php

namespace App\Models;

use App\Rules\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes; // SoftDeletes trait contain some method to make soft delete, global scope

    // what can i fill with model Category (white list)
    protected $fillable = [
        "name",
        "parent_id",
        "description",
        "slug",
        "status",
        "image",
    ];

    // Define a one-to-many relationship in categories to products
    public function products() {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    // Define an inverse relationship to the parent cotegory
    public function parent() {
        return $this->belongsTo(Category::class,'parent_id','id')
         ->withDefault([ // if no category found
                'name' => '-', // defualt value if no parent value, set name of the parent
            ]);
    }

    // Define hasMany relationship to the cotegories
    public function children() {
        return $this->hasMany(Category::class,'parent_id', 'id');
    }

    // define local scope in model to access it in the controller Coategory::filter($filters), the function name must start with scope and access it with the word after scope
    public function scopeActive( Builder $builder ) {
        $builder->where("status", "=", "active");
    }

    // define local scope in model to access it in the controller Coategory::filter($filters), the function name must start with scope to pass the builder as an argument and access it with the word after scope
    public function scopeFilter(Builder $builder, $filters) { // The Builder $builder is passed automatically by Laravel when you call the filter() scope method.
        if ($filters['name'] ?? false) { // must use false cause if no name = empty string meaning true
            $builder->where('categories.name', 'like', '%' . $filters['name'] . '%'); // must use categories.name cuse of left join
        }

        if ($filters['status'] ?? false) {
            $builder->where('categories.status', '=', $filters['status']);
        } // no return its a query builder
    }


    // use for validation just for dry
    public static function rules($id = 0)
    {
        return [

            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                "unique:categories,name,$id", // $id for  exclude the current record from the uniqueness check.


                // there are 3 ways to making custom validate
                /*
                    // 1- making custom rule without class just for here (closure)
                    function ($attribute, $value, $fail) {
                        if (strtolower($value) == "laravel") {
                            $fail("This name is forbidden!");
                        }
                }
                */

                /*
                    // 2- require custom rule form my rule classes to use it everywhere
                    new Filter(['php', 'laravel', 'js'])
                */

                // 3- making filter method just like all method in Validator class with macros approch to use it everywhere

                'filter:php,laravel,html'
            ],
            'image' => [
                'image',
                'max:1048576',
                'dimensions:min_width=100,min_height=100'
            ],
            'parent_id' => [
                'nullable',
                'int',
                'exists:categories,id'
            ],
            'status' => [
                'required',
                'in:active,archived'
            ]

        ];
    }
}
