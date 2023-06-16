<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karat2 extends Model
{
    use HasFactory;
    public $connection = 'mysql2';
    public $table = 'karats';

    protected $fillable = [
        'name_ar',
        'name_en',
        'label',
        'stamp_value',
        'transform_factor',
        'price'
    ];
}
