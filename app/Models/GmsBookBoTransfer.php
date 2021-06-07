<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookBoTransfer extends Model
{
    use HasFactory;

    protected $table = 'gms_book_bo_transfer';

     protected $fillable = [
        'iss_bo_id',
        'iss_dest_bo_id',
        'iss_cust_id',
        'iss_cust_code',
        'iss_type',
        'cnno_start',
        'cnno_end',
        'office_ro',
        'office_code',
        'dest_office_code',
        'description',
        'tranfer_type',
        'status',
        'entry_date',
        'recieved_date',
        'user_id',

  ];
}
