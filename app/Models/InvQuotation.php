<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvQuotation extends Model
{
    use HasFactory;

    protected $table = 'inv_quotation';

    protected $fillable = [
        's_id',
        'user_id',
        'quotation_invoice_no',
        'quotation_invoice_date',
        'from_address',
        'to_address',
        'basic_value',
        'amount_paid',
        'tax_type',
        'tax_percentage',
        'tax_amount',
        'others',
        'grand_total',
        'terms',
        'status',
        'account_type',
        'date',


        ];
}
