<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvQuotationItem extends Model
{
    use HasFactory;

    protected $table = 'inv_quotation_item';

    protected $fillable = [
        'quotation_id',
        'sub_cat_id',
        'products_id',
        'sub_cat_id',
        'products_id',
        'item_description',
        'item_cost',
        'item_quantity',
        'tax_type',
        'tax_percentage',
        'tax_amount',
        'posted_date',


    ];
}
