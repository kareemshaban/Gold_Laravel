<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldConvertItems extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'docId',
      'item_id',
      'karat_id',
      'weight',
      'weight21',
    ];
}
