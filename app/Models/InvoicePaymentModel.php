<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePaymentModel extends Model
{
    use HasFactory;
    protected $table = 'invoice_payments';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'invoice_head_id',
        'amount',
        'payment_method_id',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'is_deleted',
    ];
}
