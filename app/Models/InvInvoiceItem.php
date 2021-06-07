<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvInvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'inv_invoice_item';

    protected $fillable = [
        'invoice_id',
        'dc_id',
        'pf_id',
        'sub_cat_id',
        'products_id',
        'item_description',
        'item_cost',
        'item_quantity',
        'tax_type',
        'tax_percentage',
        'tax_amount',
        'status',
        'posted_date',


    ];
}
