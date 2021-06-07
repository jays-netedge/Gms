<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GmsCustomerContacts;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\URL;

class GmsCustomer extends Model
{
    use HasFactory;

    protected $table = 'gms_customer';

    protected $fillable = [
        'cust_type',
        'cust_reach',
        'cust_code',
        'cust_num',
        'cust_la_ent',
        'cust_location',
        'cust_ent',
        'cust_account_type',
        'cust_la_address',
        'cust_la_pan',
        'cust_la_servicetax',
        'cust_la_cin',
        'cust_la_cindate',
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
        'cust_cp_telno',
        'cust_cp_pan',
        'cust_cp_taxno',
        'cust_cp_vattinno',
        'cust_cp_exciseno',
        'cust_name1',
        'cust_dob1',
        'cust_education1',
        'cust_qualification1',
        'cust_residen_address1',
        'cust_fat_wife_name1',
        'cust_pan1',
        'cust_cin1',
        'cust_phone1',
        'cust_email1',
        'cust_cd_contact_name',
        'cust_cd_designation',
        'cust_cd_telno',
        'cust_cd_email',
        'cust_cd_mkt_exec',
        'cust_cd_contractno',
        'cust_cd_contract_date',
        'cust_cd_renewal_date',
        'cust_cd_exp_date',
        'cust_cd_reg_date',
        'cust_cd_rate_code',
        'cust_cd_discount',
        'cust_cd_closed',
        'cust_cd_closing_date',
        'cust_cd_remarks',
        'cust_sd_fixed',
        'cust_pb_nature',
        'cust_pb_empdeployed',
        'cust_pb_vehdeployed',
        'cust_pb_turnover',
        'cust_ad_bank_name',
        'cust_ad_bank_branch',
        'cust_ad_account_no',
        'cust_ad_ifsc_code',
        'cust_br_name',
        'cust_br_address',
        'cust_br_contact',
        'cust_br_name1',
        'cust_br_address1',
        'cust_br_contact1',
        'pan_card',
        'passport_copy',
        'driving_license',
        'st_reg_certficate',
        'aadhaar_card',
        'voter_id',
        'telephone_bill',
        'photo',
        'gallery_photo',
        'gallery_photo1',
        'gallery_photo2',
        'cust_ro',
        'cust_city',
        'cust_actual_city',
        'pincode_value',
        'service_courier',
        'service_logistics',
        'service_gold',
        'service_intracity',
        'service_international',
        'service_reverse_booking',
        'multi_region',
        'sms_status',
        'email_status',
        'cust_bill_right',
        'cust_sf_reporting',
        'sf_from_date',
        'sf_to_date',
        'sf_last_updated',
        'sf_discount_status',
        'cust_rep_office',
        'created_office_code',
        'created_office_ro',
        'scheme_rate_id',
        'delivery_code',
        'delivery_branch_status',
        'discount_code',
        'monthly_bill_type',
        'date_of_bill',
        'approved_status',
        'entry_date',
        'update_date',
        'user_id',
        'sysid',
        'gst_applicable',
        'gst_number',
        'gst_type',

    ];

    public function customerContact()
    {
        return $this->belongsTo(GmsCustomerContacts::class, 'customer_id');
    }

    public function getAadhaarCardAttribute()
    {
        if (!$this->attributes['aadhaar_card']) {
            return null;
        }
        //return 'http://localhost/gms/v1/public/customer/' . $this->attributes['aadhaar_card'];
        //return URL::to('/').$this->attributes['aadhaar_card'];
        return URL::to('/').'/backend/public/customer/'.$this->attributes['aadhaar_card'];

    }

    public function getTelephoneBillAttribute()
    {
        if (!$this->attributes['telephone_bill']) {
            return null;
        }
        return URL::to('/').'/backend/public/customer/'. $this->attributes['telephone_bill'];
    }

    public function getPanCardAttribute()
    {
        if (!$this->attributes['pan_card']) {
            return null;
        }
        return URL::to('/').'/backend/public/customer/'. $this->attributes['pan_card'];
    }

    public function getPassportCopyAttribute()
    {
        if (!$this->attributes['passport_copy']) {
            return null;
        }
        return URL::to('/').'/backend/public/customer/'. $this->attributes['passport_copy'];
    }
    public function getDrivinglicenseAttribute()
    {
        if (!$this->attributes['driving_license']) {
            return null;
        }
        return URL::to('/').'/backend/public/customer/'. $this->attributes['driving_license'];
    }

    public function getStRegCertficateAttribute()
    {
        if (!$this->attributes['st_reg_certficate']) {
            return null;
        }
        return URL::to('/').'/backend/public/customer/'. $this->attributes['st_reg_certficate'];
    }

    public function getVoterIdAttribute()
    {
        if (!$this->attributes['voter_id']) {
            return null;
        }
        return URL::to('/').'/backend/public/customer/'. $this->attributes['voter_id'];
    }

    public function getPhotoAttribute()
    {
        if (!$this->attributes['photo']) {
            return null;
        }
        return URL::to('/').'/backend/public/customer/'. $this->attributes['photo'];
    }

    public function getGalleryPhotoAttribute()
    {
        if (!$this->attributes['gallery_photo']) {
            return null;
        }
        return URL::to('/').'/backend/public/customer/'. $this->attributes['gallery_photo'];
    }

    public function getGalleryPhoto1Attribute()
    {
        if (!$this->attributes['gallery_photo1']) {
            return null;
        }
        return URL::to('/').'/backend/public/customer/'. $this->attributes['gallery_photo1'];
    }

    public function getGalleryPhoto2Attribute()
    {
        if (!$this->attributes['gallery_photo2']) {
            return null;
        }
        return URL::to('/').'/backend/public/customer/'. $this->attributes['gallery_photo2'];
    }

}
