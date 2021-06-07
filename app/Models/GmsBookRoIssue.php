<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\GmsBookBoissue;

class GmsBookRoIssue extends Model
{
    use HasFactory;

    protected $table = 'gms_book_ro_issue';

    protected $fillable = [
        'iss_zone',
        'item_id',
        'description',
        'qauantity',
        'cnno_start',
        'cnno_end',
        'total_allotted',
        'office_code',
        'status',
        'transfer_status',
        'entry_date',
        'recieved_date',
        'user_id',
        'sysid',

    ];

    public function gmsbookboissue(){
     return $this->hasMany(GmsBookBoissue::class,'iss_ro_id');
    }
}
