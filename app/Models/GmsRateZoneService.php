<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateZoneService extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_zone_service';

    protected $fillable = [
        'zone_service_type',
        'zone_book_service',
        'weight',
        'rate',
        'unique_no',
    ];
}
