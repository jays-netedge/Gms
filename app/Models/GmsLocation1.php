<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsLocation1 extends Model
{
    use HasFactory;

    protected $table = 'gms_location1';

    protected $fillable = [
        'city_code',
        'location_name',
        'pincode_value',
        'branch_id',
        'customer_id',
        'status',
        'entry_date',
        'user_id',
    ];
}
