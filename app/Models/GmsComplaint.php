<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsComplaint extends Model
{
    use HasFactory;

    protected $table = 'gms_complaint';

    protected $fillable = [
        'log_cnno',
        'consignee_mobile_no',
        'consignee_name',
        'consignor_mobile_no',
        'consignor_name',
        'description',
        'status',
        'entry_date',
        'bo_office',
        'userid',
        'is_deleted',
        'closed_description',
        'closed_by',
        'closed_date',

    ];
}
