<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsDoc extends Model
{
    use HasFactory;

    protected $table = 'gms_doc';

    protected $fillable = [
        'doc_code',
        'doc_name',
        'status',
        'entry_date',
        'user_id',
        'sysid',

    ];
}
