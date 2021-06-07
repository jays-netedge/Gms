<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateServiceFuelSfDirHistory extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_service_fuel_sf_dir_history';

    protected $fillable = [
        'scheme_rate_id',
        'original_rate_id',
        'bo_code',
        'fuel_type',
        'flat_fuel_percentage',
        'slab_fuel_id',
        'slab_fuel_from',
        'slab_fuel_to',
        'slab_fuel_percentage',
        'docket_type',
        'docket_dx',
        'docket_nx',
        'book_upto_weight',
        'book_upto_amt',
        'from_date',
        'to_date',
        'unique_no',


    ];
}
