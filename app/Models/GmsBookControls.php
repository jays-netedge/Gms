<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookControls extends Model
{
    use HasFactory;

    protected $table = 'gms_book_controls';

    protected $fillable = [
        'office_type',
        'office_code',
        'cust_type',
        'cust_code',
        'check_code',
        'from_date',
        'to_date',
        'status',
    ];
}
