<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsPincode extends Model
{
    use HasFactory;

    protected $table = 'gms_pincode';

    protected $fillable = [
        'pincode_value',
        'service',
        'city_code',
        'rep_code',
        'courier',
        'gold',
        'logistics',
        'intracity',
        'international',
        'regular',
        'topay',
        'cod',
        'topay_cod',
        'oda',
        'mentioned_piece',
        'fov_or',
        'fov_cr',
        'isc',
        'edl',
        'branch_id',
        'customer_id',
        'del_customer_id',
        'pin_status',
        'user_id',
        'posted',
        'entry_date_time',
        'update_date_time',

    ];
}
