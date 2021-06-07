<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsNdelReason extends Model
{
    use HasFactory;

    protected $table = 'gms_ndel_reason';

    protected $fillable = [
        'ndel_code',
        'ndel_desc',
        'ndel_name',
        'status',
        'entry_date',
        'update_date',
        'user_id',
        'sysid',
        'charge_flg',


    ];
}
