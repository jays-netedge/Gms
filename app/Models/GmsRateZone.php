<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateZone extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_zone';

    protected $fillable = [
        'zone_rate_id',
        'unique_zone_id',
        'service_type',
        'ro_code',
        'mode',
        'location_type',
        'org',
        'dest',
        'via',
        'rate_wt',
        'rate_amt',
        'add_rate_wt',
        'add_rate_amt',
        'status',
        'approved_status',
        'entry_date',
        'unique_no',
        'user_id',
        'sysid',

    ];
}
