<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsDesg extends Model
{
    use HasFactory;

    protected $table = 'gms_desg';

    protected $fillable = [

        'desg_code',
        'desg_name',
        'status',
        'entry_date',
        'user_id',
        'sysid',

    ];
}
