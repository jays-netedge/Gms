<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsCodeGen extends Model
{
    use HasFactory;

    protected $table = 'gms_code_gen';

    protected $fillable = [
        'CODE_TYPE',
        'STARTNO',
        'ENDNO',
        'LASTNO',
        'STATUS',
        'ENTRY_DATE',
        'USERID',
        'SYSID',

    ];
}
