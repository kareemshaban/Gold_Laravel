<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitMoney extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'doc_number',
        'date',
        'supplier_id',
        'amount',
        'payment_method',
        'user_created',
        'based_on',
        'notes',
        'price_gram',
        'type'
    ];
}
