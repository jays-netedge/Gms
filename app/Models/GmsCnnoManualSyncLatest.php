<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsCnnoManualSyncLatest extends Model
{
    use HasFactory;

    protected $table = 'gms_cnno_manual_sync_latest';

    protected $fillable = [
        'cnno',
        'booking',
        'dmf',
        'pmf',
        'exr',
        'booking_updated_date_time',
        'dmf_updated_date_time',
        'pmf_updated_date_time',


    ];
}
