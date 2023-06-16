<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemMaterials extends Model
{
    use HasFactory;
    public $fillable = [
      'id',
      'parent_id',
      'item_id'
    ];
}
