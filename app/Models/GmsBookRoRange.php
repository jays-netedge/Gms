<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookRoRange extends Model
{
    use HasFactory;

    protected $table = 'gms_book_ro_range';

    protected $fillable = [
        'iss_zone',
        'book_cat_id',
        'cnno_start',
        'cnno_end',
        'office_code',
        'status',
        'entry_date',
        'user_id',
        'sysid',
          

    ];
}
