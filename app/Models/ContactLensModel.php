<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactLensModel extends Model
{
    use HasFactory;
    protected $table = 'contact_lens';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'contact_lens_name',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_deleted',
    ];

}
