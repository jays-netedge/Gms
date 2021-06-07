<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class GmsCoMail extends Model
{
    use HasFactory, Sortable;
    
    public $timestamps = false;

    protected $table = 'gms_co_mail';

    protected $fillable = [
        'book_type',
        'book_br_code',
        'book_emp_code',
        'book_cust_type',
        'book_cust_type_orginal',
        'book_cust_code',
        'book_fr_cust_code',
        'book_mfno',
        'max_no',
        'book_mfdate',
        'book_mftime',
        'book_mftime1',
        'book_mfrefno',
        'book_srno',
        'book_cnno',
        'book_refno',
        'book_weight',
        'book_vol_lenght',
        'book_vol_breight',
        'book_vol_height',
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
        'book_cons_email',
        'book_cn_name',
        'book_cn_dtl',
        'book_cn_mobile',
        'book_cn_email',
        'book_agent',
        'book_remarks',
        'book_rate_id',
        'book_cod',
        'book_topay',
        'book_topay_inv',
        'book_billamt',
        'book_invno',
        'book_invdate',
        'book_oda_perwt',
        'book_oda_rate',
        'book_mps_rate',
        'book_mps_inv',
        'book_fov_rate',
        'book_fov_inv',
        'book_fvo_rate',
        'book_fvo_inv',
        'book_isc_rate',
        'book_isc_inv',
        'book_nsl_stype',
        'book_nsl_rate',
        'book_edl_perwt',
        'book_edl_rate',
        'book_scan_doc',
        'book_pod_scan',
        'book_pod_scan_office',
        'book_pod_scan_emp',
        'book_pod_scan_date',
        'bulk_flag',
        'book_total_amount',
        'book_current_status',
        'book_temp_office',
        'book_temp_emp',
        'booking_type',
        'bill_cust',
        'invoice_no',
        'delivery_t',
        'delivery_t_remarks',
        'delivery_t_date',
        'delivery_status',
        'entry_date',
        'status',
        'user_id',
        'sysid',
        'bill_status',

    ];

    public $sortable = ['book_mfdate',
        'book_fr_cust_code',
        'book_mfrefno'];
}
