<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookPurchaseDc extends Model
{
    use HasFactory;

    protected $table = 'gms_book_purchase_dc';

    protected $fillable = [
        'dc_no',
        'dc_date',
        'purchase_no',
        'item_id',
        'from_cnno',
        'to_cnno',
        'quantity',
        'total_allotted',
        'description',
        'status',
        'posted_date',
    ];

    public function bookItem(){
        return $this->belongsTo(GmsBookPurchaseItem::class, 'id');
    }
}
