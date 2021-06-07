<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsAlert extends Model
{
    use HasFactory;

    protected $table = 'gms_alerts';

    protected $fillable = [
        'user_id',
        'branch_type',
        'branch_code',
        'alert_type',
        'sms_status',
        'email_status',
        'name',
        'email1',
        'email2',
        'mobile1',
        'mobile2',
        'entry_date',
    ];
}
