<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvInvoice extends Model
{
    use HasFactory;

    protected $table = 'inv_invoice';

    protected $fillable = [
        's_id',
        'user_id',
        'invoice_invoice_no',
        'invoice_invoice_date',
        'po_no',
        'po_date',
        'contact_person',
        'contact_person_ph',
        'transport_person_name',
        'transport_lr_no',
        'transport_no_parcel',
        'esugun_no',
        'from_address',
        'to_address',
        'basic_value',
        'amount_paid',
        'tax_type',
        'tax_percentage',
        'tax_amount',
        'extra_rate_id',
        'extra_rate_amount',
        'others',
        'grand_total',
        'payment_terms',
        'terms',
        'status',
        'account_type',
        'invoice_dc',
        'invoice_dc_date',
        'invoice_dc_description',
        'proforma_id',
        'proforma_date',
        'type',
        'date',


    ];
}
