<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateServiceFdcCust extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_service_fdc_cust';

    protected $fillable = [
        'scheme_rate_id',
        'fran_cust_code',
        'service_type',
        'oda_type',
        'min_weight',
        'max_weight',
        'percentage',
        'amount',


    ];
}
