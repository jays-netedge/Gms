<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsCnnoStock extends Model
{
    use HasFactory;

    protected $table = 'gms_cnno_stock';

    protected $fillable = [
        'stock_cnno',
        'stock_item_id',
        'stock_iss_ro_id',
        'stock_iss_bo_id',
        'stock_iss_cust_id',
        'iss_block_id',
        'booked_status',
        'transfer_status',
        'cnno_status',
   
    ];
}
