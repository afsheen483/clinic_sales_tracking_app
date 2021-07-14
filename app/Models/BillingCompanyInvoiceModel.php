<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillingCompanyInvoiceModel extends Model
{
    use HasFactory;
    protected $table = 'billing_company_invoices';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'count_of_patients',
        'insurance_collection_amount',
        'billing_invoice_date',
        'billing_company_percent',
        'invoice_amount',
        'invoice_file',
        'invoice_title',
        'is_approved',
        'approved_by',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'is_deleted',
    ];
}
