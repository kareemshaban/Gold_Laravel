<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitOldDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'bill_id',
        'karat_id',
        'weight',
        'weight21',
        'made_money',
        'net_weight',
        'net_money',
        'gram_manufacture',
        'gram_tax',
        'gram_price'
    ];
}
