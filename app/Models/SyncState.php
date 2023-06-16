<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyncState extends Model
{
    use HasFactory;

    protected $fillable = [
      'id',
      'table',
      'table_index',
      'last_sync_id',
      'last_sync_date'
    ];
}
