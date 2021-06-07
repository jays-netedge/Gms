<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aryan extends Model
{
    use HasFactory;

    protected $table = 'aryan';

    protected $fillable = [
        'book_br_code',
        'book_emp_code',
        'book_cust_type',
        'book_cust_code',
        'book_mfno',
        'book_mfdate',
        'book_mftime',
        'book_mfrefno',
        'book_srno',
        'book_cnno',
        'book_refno',
        'book_weight',
        'book_vol_weight',
        'book_vol_lenght',
        'book_vol_breight',
        'book_pcs',
        'book_pin',
        'book_org',
        'book_dest',
        'book_location',
        'book_product_type',
        'book_mode',
        'book_doc',
        'book_service_type',
        'book_cons_addr',
        'book_cons_dtl',
        'book_cons_mobile',
        'book_cn_dtl',
        'book_cn_mobile',
        'book_cn_email',
        'book_agent',
        'book_remarks',
        'book_cod',
        'book_topay',
        'book_topay_inv',
        'book_billamt',
        'book_invno',
        'book_invdate',
        'book_oda_perwt',
        'book_oda_rate',
        'book_mps_inv',
        'book_fov_rate',
        'book_fov_inv',
        'book_fvo_rate',
        'book_fvo_inv',
        'book_isc_rate',
        'book_isc_inv',
        'book_nsl_stype',
        'book_nsl_rate',
        'book_scan_doc',
        'book_pod_scan',
        'book_total_amount',
        'book_current_status',
        'bill_cust',
        'entry_date',
        'status',
        'user_id',
        'sysid',
        'bill_status',
    ];
}
