<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatchRecipt extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'type_id',
        'amount',
        'notes',
        'date',
        'docNumber'
    ];


}
