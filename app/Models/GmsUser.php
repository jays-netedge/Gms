<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsUser extends Model
{
    use HasFactory;

    protected $table = 'gms_users';

    protected $fillable = [
        'user_name',
        'password',
        'user_type',
        'masters',
        'transactions',
        'reports',
        'billing',
        'status',
        'entry_date',
        'userid',
        'sysid',

    ];
}
