<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class GmsDmfDtls extends Model
{
    use HasFactory;

    protected $table = 'gms_dmf_dtls';

    protected $fillable = [

        'dmf_type',
        'dmf_fr_code',
        'dmf_branch',
        'dmf_emp',
        'dmf_ref_no',
        'dmf_mfno',
        'max_no',
        'dmf_mfdate',
        'dmf_mftime',
        'dmf_srno',
        'dmf_cnno',
        'dmf_cnno_current_status',
        'dmf_cnno_type',
        'dmf_pin',
        'dmf_dest',
        'dmf_wt',
        'dmf_pcs',
        'dmf_delv_amt',
        'dmf_consgn',
        'dmf_consgn_add',
        'dmf_cn_status',
        'dmf_drsno',
        'dl_name',
        'dl_relationship',
        'dl_mobile',
        'dl_phone',
        'dl_signature',
        'dl_c_signature',
        'dl_pay_chash',
        'dl_pay_cheque',
        'dl_pay_dd',
        'dl_chash_amt',
        'dl_cheque_bank_name',
        'dmf_cnno_current_status',
        'dl_cheque_no',
        'dl_cheque_amt',
        'dl_dd_bank_name',
        'dl_dd_no',
        'dl_dd_amt',
        'dmf_atmpt_date',
        'dmf_atmpt_time',
        'dmf_ndel_reason',
        'dmf_remarks',
        'dmf_cnno_remarks',
        'dmf_delv_remarks',
        'dmf_pod_status',
        'dmf_cd_no',
        'entry_date',
        'dmf_actual_date',
        'modify_date',
        'modified_by',
        'dmf_invoice_no',
        'dmf_delivery_t',
        'status',
        'userid',

    ];

    
}
