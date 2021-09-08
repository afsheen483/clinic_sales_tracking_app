<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientComplaintTrackerModel extends Model
{
    use HasFactory;
    protected $table = 'patient_complaint_tracker';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'firstname',
        'lastname',
        'staff_name',
        'location_id',
        'date_of_complaint',
        'description',
        'priority_id',
        'comments_from_manager',
        'manager_initials',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'is_deleted',
    ];
}
