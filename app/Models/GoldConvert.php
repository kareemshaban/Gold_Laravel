<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldConvert extends Model
{
    use HasFactory;
    protected $fillable = [
      'id',
      'date',
      'doc_number',
      'total21weight',
      'notes',
      'user_created'
    ];
}
