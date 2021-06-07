<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsInvoice extends Model
{
    use HasFactory;

    protected $table = 'gms_invoice';

    protected $fillable = [
        'invoice_no',
        'invoice_date',
        'month',
        'year',
        'esugun_no',
        'cust_type',
        'branch_code',
        'branch_ro',
        'customer_code',
        'from_address',
        'to_address',
        'from_date',
        'to_date',
        'monthly_bill_type',
        'ac_invoice_no',
        'fr_invoice_no',
        'fr_invoice_date',
        'fr_actual_service_charge',
        'fr_service_charge',
        'fr_less_billing_discount',
        'fr_net_service_charge',
        'fr_fuel_percentage',
        'fr_fuel_amount',
        'fr_sub_total',
        'fr_actual_less_delivery_discount',
        'fr_less_delivery_discount',
        'fr_actual_less_sf_discount',
        'fr_less_sf_discount',
        'fr_total',
        'fr_service_tax_name',
        'fr_service_tax_percentage',
        'fr_service_tax_amount',
        'fr_voucher_amount',
        'fr_grand_total',
        'total_weight',
        'total_cnno',
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
        'print_type',
        'status',
        'account_type',
        'invoice_dc',
        'invoice_edit_status',
        'invoice_dc_date',
        'invoice_dc_description',
        'proforma_id',
        'proforma_date',
        'type',
        'previous_bill',
        'invoice_status',
        'date',
    ];
}
