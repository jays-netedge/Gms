<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookPurchase extends Model
{
    use HasFactory;

    protected $table = 'gms_book_purchase';

    protected $fillable = [
        'user_id',
        'purchase_invoice_no',
        'from_address',
        'from_tin',
        'to_address',
        'to_tin',
        'basic_value',
        'amount_paid',
        'tax_type',
        'tax_percentage',
        'tax_amount',
        'others',
        'grand_total',
        'terms',
        'book_cat_type',
        'account_type',
        'purchase_invoice_date',
        'status',
        'account_type',
        'date',
        'is_deleted',

    ];

    
}
