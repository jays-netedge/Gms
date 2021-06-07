<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookCatRange extends Model
{
    use HasFactory;

    protected $table = 'gms_book_cat_range';

    protected $fillable = [
        'book_cat_id',
        'cnno_start',
        'cnno_end',
        'upto_weight',
        'upto_amt',
        'status',
        'entry_date',
        'user_id',
    ];
}
