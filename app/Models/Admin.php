<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends User
{
    use HasFactory, Notifiable, HasApiTokens; // for notification

    protected $fillable = [
        "name",
        "email",
        "password",
        "phone_number",
        "super_admin",
        "status"
    ];
}
