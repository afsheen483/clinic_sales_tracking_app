<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicModel extends Model
{
    use HasFactory;
    protected $table = 'clinics';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'clinic_name',
        'address',
        'location',
        'phone_no',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'is_deleted',
    ];
}
