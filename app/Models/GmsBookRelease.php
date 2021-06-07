<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookRelease extends Model
{
    use HasFactory;

    protected $table = 'gms_book_release';

    protected $fillable = [
        'description',
        'multiple_cnno',
        'cnno_start',
        'cnno_end',
        'block_type',
        'status',
        'entry_date',
        'user_id',

    ];
}
