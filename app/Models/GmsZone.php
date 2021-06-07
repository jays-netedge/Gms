<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsZone extends Model
{
    use HasFactory;

    protected $table = 'gms_zone';

    protected $fillable = [
        'country_id',
        'zone_code',
        'zone_name',
        'zone_incharge',
        'status',
        'entry_date',
        'user_id',
        'sys_id',


    ];
}
