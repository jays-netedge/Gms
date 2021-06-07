<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GmsBookCustIssue;

class GmsBookBoissue extends Model
{
    use HasFactory;

    protected $table = 'gms_book_bo_issue';

    protected $fillable = [
        'iss_ro_id',
        'office_type',
        'qauantity',
        'cnno_start',
        'cnno_end',
        'total_allotted',
        'office_code',
        'rate_per_cnno',
        'status',
        'transfer_status',
        'entry_date',
        'recieved_date',
        'user_id',
        'sysid',
    ];

    public function gmsbookcustissue(){
    return $this->hasMany(GmsBookCustIssue::class,'iss_bo_id');
}
}
