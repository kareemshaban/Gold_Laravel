<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleViews extends Model
{
    use HasFactory;
    protected $fillable = [
      'id',
      'role_id',
      'view_id',
        'all_auth',
        'save_auth',
        'edit_auth',
        'delete_auth',
        'preview_auth'
    ];
}
