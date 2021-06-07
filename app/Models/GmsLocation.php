<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsLocation extends Model
{
    use HasFactory;

    protected $table = 'gms_location';

    protected $fillable = [
        'city_code',
        'location_name',
        'pincode_value',
        'office_type',
        'city_under',
        'service',
        'branch_id',
        'customer_id',
        'del_customer_id',
        'status',
        'entry_date',
        'user_id',
    ];
}
