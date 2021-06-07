<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateMasterMumro extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_master_mumro';

    protected $fillable = [
        'unique_rate_id',
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
        'addnl_type',
        'addnl_wt',
        'addnl_min',
        'addnl_max',
        'addnl_fixed',
        'addnl_rate',
        'extra_rate',
        'tat',
        'status',
        'approved_status',
        'entry_date',
        'user_id',
        'sysid',


    ];
}
