<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateZoneInvoice extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_zone_invoice';

    protected $fillable = [
        'zone_inv_id',
        'ro_id',
        'to_ro',
        'month',
        'year',
        'zone_inv_type',
        'zone_unique_no',
        'posted_date',
    ];
}
