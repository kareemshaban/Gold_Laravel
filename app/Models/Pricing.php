<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    use HasFactory;
    protected $fillable = [
        'last_Update',
        'user_update',
        'price',
        'price_21',
        'price_22',
        'price_24',
        'price_18',
        'price_14',
        'currency'
    ];
}
