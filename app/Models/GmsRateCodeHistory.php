<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateCodeHistory extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_code_history';

    protected $fillable = [
        'rate_code',
        'rate_type_card',
        'rate_type',
        'rate_name',
        'office_code',
        'cust_code',
        'description',
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
        'effect_date_from',
        'effect_date_to',
        'status',
        'entry_date',
        'user_id',
        'sysid',
        'from_date',
        'to_date',
        'unique_no',


    ];
}
