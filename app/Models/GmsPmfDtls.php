<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsPmfDtls extends Model
{
    use HasFactory;

    protected $table = 'gms_pmf_dtls';

    protected $fillable = [
        'pmf_no',
        'max_no',
        'pmf_type',
        'pmf_date',
        'pmf_time',
        'pmf_emp_code',
        'pmf_origin',
        'pmf_dest',
        'pmf_mode',
        'pmf_doc',
        'pmf_amt',
        'pmf_srno',
        'pmf_cnno',
        'pmf_cnno_type',
        'pmf_wt',
        'pmf_vol_wt',
        'pmf_actual_wt',
        'pmf_received_wt',
        'pmf_vol_received_wt',
        'pmf_actual_received_wt',
        'pmf_pcs',
        'pmf_received_pcs',
        'pmf_pin',
        'pmf_city',
        'pmf_remarks',
        'pmf_entry_date',
        'pmf_status',
        'pmf_receieved_emp',
        'pmf_received_by',
        'pmf_received_date',
        'pmf_recieved_type',
        'pmf_mfed',
        'pmf_transport_type',
        'pmf_ro',
        'pmf_dest_ro',
        'pmf_recevied_ro',
        'pmf_cd_no',
        'pmf_misroute',
        'changed_direct_emp',
        'changed_original_dest_location',
        'userid',


    ];
}
