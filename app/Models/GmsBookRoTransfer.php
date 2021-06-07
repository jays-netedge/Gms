<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookRoTransfer extends Model
{
    use HasFactory;

    protected $table = 'gms_book_ro_transfer';

    protected $fillable = [
        'iss_ro_id',
        'iss_dest_ro_id',
        'cnno_start',
        'cnno_end',
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
