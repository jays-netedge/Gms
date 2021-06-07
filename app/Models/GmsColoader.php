<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsColoader extends Model
{
    use HasFactory;

    protected $table = 'gms_coloader';

    protected $fillable = [
        'coloader_type',
        'coloader_code',
        'coloader_name',
        'coloader_add1',
        'coloader_add2',
        'coloader_contact',
        'coloader_phone',
        'coloader_rep_offtype',
        'coloader_rep_office',
        'coloader_ro',
        'status',
        'entry_date',
        'user_id',
        'sysid',
   
    ];
}
