<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactLensTrackerModel extends Model
{
    use HasFactory;
    protected $table = 'contact_lens_tracker';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'invoice_head_id',
        'lens_status_id',
        'contact_lens_id',
    ];
}
