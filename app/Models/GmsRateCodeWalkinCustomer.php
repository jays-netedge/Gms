<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateCodeWalkinCustomer extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_code_walkincustomer';

    protected $fillable = [
        'description',
        'fuel_type',
        'flat_fuel_percentage',
        'slab_fuel_id',
        'slab_fuel_from',
        'slab_fuel_to',
        'slab_fuel_percentage',
        'effect_date_from',
        'effect_date_to',
        'status',
        'entry_date',
        'user_id',


    ];
}
