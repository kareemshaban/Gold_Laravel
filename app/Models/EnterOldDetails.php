<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnterOldDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'bill_id',
        'karat_id',
        'gram_price',
        'weight',
        'weight21',
        'made_money',
        'net_weight',
        'net_money',
    ];
}
