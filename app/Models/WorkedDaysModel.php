<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkedDaysModel extends Model
{
    use HasFactory;
    protected $table = 'worked_days';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'year',
        'week_no',
        'labor_hours',
        'doctor_days',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'is_deleted',
    ];
}
