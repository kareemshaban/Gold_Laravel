<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnterMoney extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'doc_number',
        'date',
        'client_id',
        'amount',
        'payment_method',
        'user_created',
        'based_on',
        'notes',
        'based_on_bill_number'
    ];
}
