<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsExrDtls extends Model
{
    use HasFactory;

    protected $table = 'gms_exr_dtls';

    protected $fillable = [
        'exr_no',
        'exr_type',
        'exr_date',
        'exr_time',
        'exr_cnno',
        'exr_wt',
        'exr_vol_wt',
        'exr_pcs',
        'exr_remarks',
        'exr_origin_branch',
        'exr_origin_ro',
        'exr_receieved_emp',
        'exr_received_by',
        'exr_recevied_ro',
        'exr_received_date',
        'exr_received_type',
    ];
}
