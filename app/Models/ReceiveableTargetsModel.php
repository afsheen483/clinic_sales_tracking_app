<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiveableTargetsModel extends Model
{
    use HasFactory;
    protected $table = 'receiveable_targets';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'time_period_from',
        'time_period_to',
        'unpaid_target_percent',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'is_deleted',
    ];
}
