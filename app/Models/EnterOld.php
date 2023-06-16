<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnterOld extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'bill_number',
        'date',
        'client_id',
        'total_money',
        'total21_gold',
        'paid_money',
        'remain_money',
        'paid_gold',
        'remain_gold',
        'notes',
        'user_created',
        'discount',
        'net_money',
        'pos',
        'tax'
    ];
}
