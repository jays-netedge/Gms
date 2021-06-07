<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsApi extends Model
{
    use HasFactory;

    protected $table = 'gms_api';

    protected $fillable = [
        'vendor_name',
        'api_url',
        'token',
        'customer_code',
        'from_time',
        'to_time',
        'status',
        'update_date',
        'entry_date',
    ];
}
