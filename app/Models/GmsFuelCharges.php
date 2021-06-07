<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsFuelCharges extends Model
{
    use HasFactory;

    protected $table = 'gms_fuel_charges';

    protected $fillable = [
        'barrel_price_index',
        'from_price',
        'to_price',
        'charged_percentage',
        'posted_month',

    ];
}
