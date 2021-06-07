<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateBillingDiscount extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_billing_discount';

    protected $fillable = [
        'max_no',
        'billing_rate_code',
        'billing_type',
        'delivery_type',
        'discount_type',
        'rate_per_cnno',
        'rate_per_weight',
        'invoice_value_range',
        'invoice_value_percentage',
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
        'created_date',
        'created_by',
    ];
}
