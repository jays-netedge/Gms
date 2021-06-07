<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsFuelDefaultCharges extends Model
{
    use HasFactory;

    protected $table = 'gms_fuel_default_charges';

    protected $fillable = [
        'fuel_price',
        'fuel_date_from',
        'fuel_date_to',


    ];
}
