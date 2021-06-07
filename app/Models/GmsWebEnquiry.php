<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsWebEnquiry extends Model
{
    use HasFactory;

    protected $table = 'gms_web_enquiry';

    protected $fillable = [
        'enquiry_name',
        'enquiry_company',
        'enquiry_address',
        'enquiry_district',
        'enquiry_pincode',
        'enquiry_country',
        'enquiry_state',
        'enquiry_city',
        'enquiry_tel_no',
        'enquiry_mobile_no',
        'enquiry_fax_no',
        'enquiry_email_id',
        'enquiry_known_us',
        'enquiry_applicant_details',
        'posted_date',
        'enquiry_type',

    ];
}
