<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceModel extends Model
{
    use HasFactory;
    protected $table = 'insurances';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'insurance_title',
        'created_by',
        'created_at',
        'modified_at',
        'modified_by',
        'is_deleted',
    ];

}
