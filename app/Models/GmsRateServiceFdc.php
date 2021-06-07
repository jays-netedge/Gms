<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateServiceFdc extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_service_fdc';

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
