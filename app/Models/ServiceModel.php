<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceModel extends Model
{
    use HasFactory;
    protected $table = 'services';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'title',
        'unit_price',
        'is_insured',
        'is_product',
        'created_by',
        'created_at',
        'modified_at',
        'modified_by',
        'is_deleted',
    ];
}
