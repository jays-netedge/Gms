<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsOfficeTimeBlock extends Model
{
    use HasFactory;

    protected $table = 'gms_office_time_block';

    protected $fillable = [
        'office_type',
        'office_code',
        'block_type',
        'from_time',
        'to_time',
        'status',
        'created_by',
        'created_date',


    ];
}
