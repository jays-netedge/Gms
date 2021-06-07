<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsProduct extends Model
{
    use HasFactory;

    protected $table = 'gms_product';

    protected $fillable = [
        'product_code',
        'product_name',
        'status',
        'entry_date',
        'user_id',
        'sysid',


    ];
}
