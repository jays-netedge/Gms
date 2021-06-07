<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateService extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_service';

    protected $fillable = [
        'scheme_rate_id',
        'service_type',
        'oda_type',
        'min_weight',
        'max_weight',
        'percentage',
        'amount',


    ];
}
