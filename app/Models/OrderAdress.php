<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Intl\Countries;

class OrderAdress extends Model
{
    use HasFactory;

    protected $fillable = [
        "order_id",
        'type',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'street_address',
        'city',
        'postal_code',
        'state',
        'country',
    ];

    public $timestamps = false;

    // Accessor
    public function getNameAttribute() {
        return "{$this->first_name} {$this->last_name}";
    }
    public function getCountryNameAttribute() {
        return Countries::getName($this->country); // Gets the country name from its alpha2 code.
    }

}
