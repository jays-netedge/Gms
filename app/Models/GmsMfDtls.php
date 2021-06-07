<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsMfDtls extends Model
{
    use HasFactory;

    protected $table = 'gms_mf_dtls';

    protected $fillable = [
        'mf_type',
        'mf_date',
        'mf_time',
        'mf_emp_code',
        'mf_origin_type',
        'mf_origin',
        'mf_dest_type',
        'mf_dest',
        'mf_mode',
        'mf_srno',
        'mf_pmfno',
        'mf_wt',
        'mf_vol_wt',
        'mf_actual_wt',
        'mf_pcs',
        'mf_pmf_dest',
        'mf_remarks',
        'mf_entry_date',
        'mf_status',
        'mf_receieved_emp',
        'mf_received_by',
        'mf_received_date',
        'mf_transport_type',
        'mf_ro',
        'mf_dest_ro',
        'mf_recevied_ro',
        'mf_cd_no',
        'mf_misroute',
        'changed_direct_emp',
        'changed_original_dest_location',
        'userid',


    ];
}
