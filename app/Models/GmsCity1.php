<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsCity1 extends Model
{
    use HasFactory;
    
    protected $table = 'gms_city1';

    protected $fillable = [
        'city_code',
        'city_name',
        'state_id',
        'state_code',
        'metro',
        'city_rep_bo',
        'status',
        'entry_date',
        'user_id',
        'sysid',

    ];
}
