<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookZonalIssue extends Model
{
    use HasFactory;

    protected $table = 'gms_book_zonal_issue';

    protected $fillable = [
        'iss_zone',
        'office_id',
        'purchase_id',
        'cnno_start',
        'cnno_end',
        'status',
        'entry_date',
        'user_id',
        'sysid',

    ];
}
