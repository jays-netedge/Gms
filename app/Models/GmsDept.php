<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsDept extends Model
{
    use HasFactory;

    protected $table = 'gms_dept';

    protected $fillable = [

        'dept_code',
        'dept_name',
        'status',
        'entry_date',
        'user_id',
        'sysid',

    ];
}
