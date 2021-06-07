<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsState extends Model
{
    use HasFactory;

    protected $table = 'gms_state';

    protected $fillable = [
        'country_id',
        'zone_id',
        'state_code',
        'state_name',
        'status',
        'entry_date',
        'user_id',
        'sys_id',


    ];
}
