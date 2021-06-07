<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsInvoiceCust extends Model
{
    use HasFactory;

    protected $table = 'gms_invoice_cust';

    protected $fillable = [
        'cust_invoice_no',
        'cust_invoice_date',
        'cust_code',
        'fran_cust_code',
        'from_date',
        'to_date',
        'invoice_status',
        'date',


    ];
}
