<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storehouse extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'address',
        'tax_number',
        'commercial_registration',
        'serial_prefix'
    ];
}
