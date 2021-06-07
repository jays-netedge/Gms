<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookCustIssue extends Model
{
    use HasFactory;

    protected $table = 'gms_book_cust_issue';

    protected $fillable = [
        'iss_bo_id',
        'cust_type',
        'cust_code',
        'description',
        'qauantity',
        'cnno_start',
        'cnno_end',
        'total_allotted',
        'office_code',
        'office_ro',
        'created_by',
        'rate_per_cnno',
        'status',
        'transfer_status',
        'completed_status',
        'entry_date',
        'recieved_date',
        'user_id',
        'sysid',
    ];
}
