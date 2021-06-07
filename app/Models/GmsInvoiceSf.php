<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsInvoiceSf extends Model
{
    use HasFactory;

    protected $table = 'gms_invoice_sf';

    protected $fillable = [
        'invoice_no',
        'customer_code',
        'customer_type',
        'reg_booking',
        'reg_amt',
        'direct_booking',
        'direct_amt',
        'total_cnno',
        'total_amt',
    ];
}
