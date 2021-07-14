<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingCompanyInvoiceDetailModel extends Model
{
    use HasFactory;
    protected $table = 'billing_company_invoices_details';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'invoice_head_id',
        'billing_company_invoice_id',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'is_deleted',
    ];
}
