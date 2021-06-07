<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GmsCustomer;

class GmsCustomerContacts extends Model
{
	use HasFactory;

	protected $table = 'gms_customer_contacts';

	protected $fillable = [
		'cust_contact_id',
		'cust_code',
		'cust_type',
		'cust_name',
		'cust_dob',
		'cust_education',
		'cust_qualification',
		'cust_residen_address',
		'cust_fat_wife_name',
		'cust_pan',
		'cust_cin',
		'cust_phone',
		'cust_email',
		'cust_fax',
		'cust_telno',
		'cust_cp_name',
		'cust_cp_pan',
		'cust_cp_taxno',
		'cust_cp_vattinno',
		'cust_cp_exciseno',

	];

	public function customer()
	{
		return $this->belongsTo(GmsCustomer::class, 'id');
	}
}
