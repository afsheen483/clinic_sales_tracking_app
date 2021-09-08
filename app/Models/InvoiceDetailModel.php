<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetailModel extends Model
{
    use HasFactory;
    protected $table = 'invoice_details';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'service_id',
        'invoice_head_id',
        'quantity',
        'pro_unit_price',
        'copayment',
        'insurance_payment',
        'contact_lens_id',
        'insurance_payment_entered_by',
        'insurance_payment_entered_at',
        'modified_at',
        'modified_by',
    ];
}
