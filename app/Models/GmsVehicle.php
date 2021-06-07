<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsVehicle extends Model
{
    use HasFactory;

    protected $table = 'gms_vehicle';

    protected $fillable = [
        'veh_no',
        'veh_made',
        'veh_provider',
        'veh_remarks',
        'status',
        'entry_date',
        'user_id',
        'susid',

    ];
}
