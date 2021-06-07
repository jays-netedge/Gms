<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsComplaintReply extends Model
{
    use HasFactory;

    protected $table = 'gms_complaint_reply';

    protected $fillable = [
        'log_no',
        'description',
        'entry_date',
        'userid',
        'is_deleted'

    ];
}
