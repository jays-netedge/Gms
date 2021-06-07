<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlUser extends Model
{
    use HasFactory;

    protected $table = 'sl_users';

    protected $fillable = [
        'group_id',
        'company',
        'dob',
        'city',
        'person',
        'phone',
        'email',
        'designation',
        'department',
        'status',
        'posted_date',


    ];
}
