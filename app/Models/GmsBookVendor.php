<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookVendor extends Model
{
    use HasFactory;

    protected $table = 'gms_book_vendor';

    protected $fillable = [
        'vendor_code',
        'company',
        'address1',
        'address2',
        'city',
        'pincode',
        'person',
        'con_num1',
        'con_num2',
        'email',
        'email1',
        'fax',
        'tin_no',
        'bank_name',
        'bank_branch_name',
        'bank_account_no',
        'posted_date',
    ];
}
