<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookZonalRange extends Model
{
    use HasFactory;

    protected $table = 'gms_book_ro_range';

    protected $fillable = [
        'iss_zone',
        'cnno_start',
        'cnno_end',
        'status',
        'entry_date',
        'user_id',
        'sysid',

    ];
}
