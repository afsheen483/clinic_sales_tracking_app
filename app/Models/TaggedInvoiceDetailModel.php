<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaggedInvoiceDetailModel extends Model
{
    use HasFactory;
    protected $table = 'tagged_invoices_details';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'invoice_head_id',
        'tagged_by',
        'is_paid',
        'marked_paid_by',
        'marked_at',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
       
    ];
}
