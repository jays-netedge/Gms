<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsColoaderDtls extends Model
{
    use HasFactory;

    protected $table = 'gms_coloader_dtls';
    public $timestamps = false;

    protected $fillable = [
        'c_type',
        'branch_code',
        'branch_emp_code',
        'branch_ro',
        'coloader_code',
        'cd_no',
        'coloader_srno',
        'coloader_name',
        'coloader_phone',
        'coloader_mobile',
        'coloader_bus_no',
        'coloader_description',
        'cd_bags',
        'coloader_wt',
        'coloader_mode',
        'coloader_date',
        'coloader_dest',
        'coloader_dest_ro',
        'coloader_dest_bo',
        'coloader_dest_city',
        'coloader_type',
        'coloader_cust_type',
        'coloader_cust_code',
        'manifest_no',
        'manifest_date',
        'total_cnno',
        'total_wt',
        'remark',
        'entry_date',
        'status',
        'userid',
   
    ];
}
