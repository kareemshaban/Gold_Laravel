<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category2 extends Model
{
    use HasFactory;
    public $connection = 'mysql2';
    public $table = 'categories';
    protected $fillable = [
        'name_ar',
        'name_en',
        'description',
        'image_url',
        'parent_id'
    ];

}
