<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateZoneServiceHistory extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_zone_service_history';

    protected $fillable = [
        'zone_service_type',
        'zone_book_service',
        'weight',
        'rate',
        'from_date',
        'to_date',
        'unique_no',
    ];
}
