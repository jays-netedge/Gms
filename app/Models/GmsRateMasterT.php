<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateMasterT extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_master_t';

    protected $fillable = [
        'product_code',
        'scheme_rate_id',
        'ro_code',
        'bo_code',
        'cust_type',
        'org',
        'dest',
        'mode',
        'doc_type',
        'loc_type',
        'flat_rate',
        'slab_rate',
        'min_charge_wt',
        'from_wt',
        'to_wt',
        'rate',
        'tranship_rate',
        'addnl',
        'addnl_wt',
        'addnl_rate',
        'tat',
        'status',
        'approved_status',
        'entry_date',
        'user_id',
        'sysid',


    ];
}
