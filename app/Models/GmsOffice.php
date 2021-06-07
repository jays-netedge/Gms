<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsOffice extends Model
{
    use HasFactory;

    protected $table = 'gms_office';

    protected $fillable = [
        'office_code',
        'office_name',
        'office_type',
        'branch_category',
        'office_under',
        'office_flag',
        'office_ent',
        'office_add1',
        'office_add2',
        'office_city',
        'office_pin',
        'office_location',
        'office_phone',
        'office_fax',
        'office_email',
        'office_contact',
        'office_contractno',
        'office_contract_date',
        'office_renewal_date',
        'office_exp_date',
        'office_sec_deposit',
        'office_pan',
        'office_stax_no',
        'office_stax_date',
        'office_bank_name',
        'office_bank_branch_name',
        'office_bank_accno',
        'office_bank_ifsc',
        'office_bank_micrno',
        'office_bank_address',
        'office_closed',
        'office_closing_date',
        'office_sf_flag',
        'office_remarks',
        'office_reporting',
        'office_walkin',
        'office_cnnogen',
        'office_delt',
        'status',
        'login_assigned',
        'entry_date',
        'update_date',
        'user_id',
        'sys_id',
        'gst_tin',


    ];
}
