<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyMovement extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'company_id',
        'paid_money',
        'credit_money',
        'debit_money',
        'paid_gold',
        'credit_gold',
        'debit_gold',
        'date',
        'invoice_type',
        'bill_id',
        'bill_number',
        'user_created',
    ];
}
