<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsCustomer2 extends Model
{
    use HasFactory;

    protected $table = 'gms_customer2';

    protected $fillable = [
          'cust_code', 
		  'cust_name', 
		  'cust_type', 
		  'cust_ent',
		  'cust_add1', 
		  'cust_add2',
		  'cust_city', 
		  'cust_pin', 
		  'cust_phone',
		  'cust_fax', 
		  'cust_email',
		  'cust_contact',
		  'cust_contractno',
		  'cust_contract_date', 
		  'cust_renewal_date',
		  'cust_exp_date',
		  'cust_mkt_exec',
		  'cust_pan', 
		  'cust_staxno', 
		  'cust_stax_date', 
		  'cust_discount', 
		  'cust_closed', 
		  'cust_closing_date',
		  'cust_remarks',
		  'cust_secdip_fixed', 
		  'cust_secdip_paid', 
		  'cust_sec_chequeno',
		  'cust_sec_chequedate',
		  'cust_royl_amt', 
		  'cust_royl_date',
		  'cust_ro', 
		  'cust_rep_office', 
		  'cust_rate_code', 
		  'cust_web_push',
		  'cust_oda_charges', 
		  'satus', 
		  'cust_reach', 
		  'created_office_code', 
		  'address_proof', 
		  'pan_card', 
		  'st_reg_certficate', 
		  'photo', 
		  'deposit_DD',
		  'scheme_rate_id', 
		  'approved_status', 
		  'entry_date', 
		  'user_id', 
		  'sysid', 
   
    ];
}
