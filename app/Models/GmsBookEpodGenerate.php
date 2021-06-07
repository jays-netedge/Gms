<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookEpodGenerate extends Model
{
    use HasFactory;

    protected $table = 'gms_book_epod_generate';

    protected $fillable = [
        'cnn_no',
        'iss_cust_id',
        'unique_no',
        'booking_branch',
        'consignor_name',
        'consignor_mobile',
        'consignor_email',
        'consignor_pincode',
        'consignor_address',
        'consignee_office_name',
        'consignee_name',
        'consignee_address',
        'consignee_pincode',
        'consignee_mobile',
        'consignee_email',
        'cust_code',
        'ref_no',
        'doc_type',
        'no_pcs',
        'value_declared',
        'cod_value',
        'cust_epod_remarks',
        'status',
        'entry_date',
    ];
}
