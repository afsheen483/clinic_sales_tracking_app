<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaggedInvoicesModel extends Model
{
    use HasFactory;
    protected $table = 'tagged_invoices';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'tagged_by',
        'is_paid',
        'marked_paid_by',
        'remarks',
        'invoice_head_id',
        'marked_at',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'is_deleted',
    ];
}
