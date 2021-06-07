<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsGstHistory extends Model
{
    use HasFactory;

    protected $table = 'gms_gst_history';

    protected $fillable = [
        'gst_code',
        'gst_rate',
        'from_date',
        'to_date',


    ];
}
