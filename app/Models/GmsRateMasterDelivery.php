<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateMasterDelivery extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_master_delivery';

    protected $fillable = [
        'max_no',
        'del_rate_code',
        'flat_rate',
        'slab_rate',
        'from_wt',
        'to_wt',
        'rate',
        'addnl',
        'addnl_wt',
        'addnl_rate',
        'non_from_wt',
        'non_to_wt',
        'non_rate',
        'non_addnl',
        'non_addnl_wt',
        'non_addnl_rate',
        'gd_from_wt',
        'gd_to_wt',
        'gd_rate',
        'gd_addnl',
        'gd_addnl_wt',
        'gd_addnl_rate',
        'gd_non_from_wt',
        'gd_non_to_wt',
        'gd_non_rate',
        'gd_non_addnl',
        'gd_non_addnl_wt',
        'gd_non_addnl_rate',
        'lg_from_wt',
        'lg_to_wt',
        'lg_rate',
        'lg_addnl',
        'lg_addnl_wt',
        'lg_addnl_rate',
        'lg_non_from_wt',
        'lg_non_to_wt',
        'lg_non_rate',
        'lg_non_addnl',
        'lg_non_addnl_wt',
        'lg_non_addnl_rate',
        'max_limit_wt',
        'max_limit_price',
        'non_max_limit_wt',
        'non_max_limit_price',
        'gd_max_limit_wt',
        'gd_max_limit_price',
        'gd_non_max_limit_wt',
        'gd_non_max_limit_price',
        'lg_max_limit_wt',
        'lg_max_limit_price',
        'lg_non_max_limit_wt',
        'lg_non_max_limit_price',
        'tpy',
        'cod',
        'mps',
        'fvo',
        'fov',
        'edl',
        'isc',
        'oda',
        'status',
        'entry_date',
        'created_by',


    ];
}
