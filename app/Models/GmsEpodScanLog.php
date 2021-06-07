<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsEpodScanLog extends Model
{
    use HasFactory;

    protected $table = 'gms_epod_scan_log';

    protected $fillable = [
        'ref_no',
        'scanned_by',
        'user_type',
        'scaned_date_time',
        'cust_code',
        'ip_address',
    ];
}
