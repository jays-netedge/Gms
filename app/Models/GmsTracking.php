<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsTracking extends Model
{
    use HasFactory;

    protected $table = 'gms_tracking';

    protected $fillable = [
        'ID',
        'TYPE',
        'CNNO',
        'REFNO',
        'MFNO',
        'ORG',
        'DEST',
        'WEIGHT',
        'MODE',
        'DOC_TYPE',
        'CN_STATUS',
        'REMARKS',
        'DATE',
        'TIME',
        'CUST_CODE',

    ];
}
