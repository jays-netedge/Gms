<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookBlock extends Model
{
    use HasFactory;

    protected $table = 'gms_book_block';

    protected $fillable = [
        'user_id',
        'description',
        'multiple_cnno',
        'cnno_start',
        'cnno_end',
        'block_type',
        'status',
        'created_by',
        'entry_date',
    ];
}
