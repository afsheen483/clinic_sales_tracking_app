<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceHeadModel extends Model
{
    use HasFactory;
    protected $table = 'invoice_head';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'insurance_balance',
        'total_balance',
        'patient_firstname',
        'patient_lastname',
        'clinic_id',
        'doctor_id',
        'primary_insurance_id',
        'secondary_insurance_id',
        'is_out_of_pocket',
        'family_upsell',
        'is_completed',
        'invoice_amount',
        'invoice_date',
        'remarks',
        'claim_status',
        'patient_balance',
        'discount',
        'created_at',
        'created_by',
        'modified_at',
        'modified_by',
        'is_deleted',
    ];

    public function invoiceDetails()
   {
       return $this->belongsTo('App\Models\InvoiceDetailModel','invoice_head_id','id');
   }
   public function insurance_primary()
   {
       return $this->belongsTo('App\Models\InsuranceModel','primary_insurance_id','id');
   }
   public function insurance_secondary()
   {
       return $this->belongsTo('App\Models\InsuranceModel','secondary_insurance_id','id');
   }
}
