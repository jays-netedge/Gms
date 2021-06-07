<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookPurchaseItem extends Model
{
    use HasFactory;

    protected $table = 'gms_book_purchase_item';

    protected $fillable = [
        'purchase_id',
        'book_cat_id',
        'from_range',
        'to_range',
        'total_allotted',
        'item_description',
        'item_cost',
        'item_quantity',
        'tax_type',
        'tax_percentage',
        'tax_amount',
        'stock_status',
        'posted_date',
    ];

    public function bookDc(){
        return $this->belongsTo(GmsBookPurchaseDc::class, 'item_id');
    }
}
