<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item2 extends Model
{
    use HasFactory;
    public $connection = 'mysql2';
    public $table = 'items';
    protected $fillable = [
        'code',
        'name_ar',
        'name_en',
        'category_id',
        'karat_id',
        'weight',
        'no_metal',
        'no_metal_type',
        'made_Value',
        'item_type',
        'tax',
        'state',
        'img',
        'price',
        'cost',
        'quantity',
         'material'

    ];

       public function karat(){
          return $this -> belongsTo(Karat::class ,'karat_id' );
       }

    public function category(){
        return $this -> belongsTo(Category::class ,'category_id' );
    }
}
