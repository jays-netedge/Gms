<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateServiceSfRegHistory extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_service_sf_reg_history';

    protected $fillable = [
        'scheme_rate_id',
        'bo_code',
        'service_type',
        'oda_type',
        'min_weight',
        'max_weight',
        'percentage',
        'amount',
        'from_date',
        'to_date',
        'unique_no',

    ];
}
