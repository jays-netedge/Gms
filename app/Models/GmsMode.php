<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsMode extends Model
{
    use HasFactory;

    protected $table = 'gms_mode';

    protected $fillable = [
        'mode_code',
        'mode_name',
        'status',
        'entry_date',
        'user_id',
        'sysid',

    ];
}
