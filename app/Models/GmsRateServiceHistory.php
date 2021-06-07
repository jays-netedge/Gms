<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateServiceHistory extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_service_history';

    protected $fillable = [
        'scheme_rate_id',
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
