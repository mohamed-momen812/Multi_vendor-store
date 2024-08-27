<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        "name",
        "slug"
    ];

    public function products() {
        return $this->belongsToMany(Product::class); // no need to pass the data becuase i use the defualt name of the tables
    }


}
