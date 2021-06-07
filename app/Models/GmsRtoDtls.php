<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRtoDtls extends Model
{
    use HasFactory;

    protected $table = 'gms_rto_dtls';

    protected $fillable = [
        'rto_branch',
        'rto_recv_type',
        'rto_recv_code',
        'rto_mfno',
        'rto_mfdate',
        'rto_mftime',
        'rto_srno',
        'rto_cnno',
        'rto_reason',
        'rto_remarks',
        'entry_date',
        'status',
        'userid',
    ];
}
