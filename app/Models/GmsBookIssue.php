<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookIssue extends Model
{
    use HasFactory;

    protected $table = 'gms_book_issue';

    protected $fillable = [
        'iss_type',
        'iss_code',
        'iss_date',
        'cnno_start',
        'cnno_end',
        'status',
        'entry_date',
        'user_id',
        'sysid',
        'book_type',

    ];
}
