<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsConsignmentBlock extends Model
{
    use HasFactory;

    protected $table = 'gms_consignment_block';

    protected $fillable = [
        'cnno_block',
        'description',
        'status',
        'created_by',
        'entry_date',
   
    ];
}
