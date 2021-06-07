<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsCustomerFranchisee extends Model
{
    use HasFactory;

    protected $table = 'gms_customer_franchisee';

    protected $fillable = [
          
		  'fran_cust_inc', 
		  'cust_code', 
		  'fran_cust_code',
		  'fran_cust_city', 
		  'fran_cust_name', 
		  'fran_cust_email', 
		  'created_branch', 
		  'rate_card_status', 
		  'entry_date', 
		  'user_id',
   
    ];
}
