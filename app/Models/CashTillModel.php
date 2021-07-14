<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTillModel extends Model
{
    use HasFactory;
    protected $table = 'cash_till';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'clinic_id',
        'cash_till_date',
        'opening_balance',
        'given_money',
        'cash_received_today',
        'any_refunds',
        'extra_money_added',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'manager_id',
        'end_balance',
    ];
}
