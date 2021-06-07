<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateServiceSfDir extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_service_sf_dir';

    protected $fillable = [
        'scheme_rate_id',
        'bo_code',
        'service_type',
        'oda_type',
        'min_weight',
        'max_weight',
        'percentage',
        'amount',


    ];
}
