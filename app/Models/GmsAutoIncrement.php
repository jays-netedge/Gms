<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsAutoIncrement extends Model
{
    use HasFactory;

    protected $table = 'gms_auto_increment';

    protected $fillable = [
        'office_code',
        'table_name',
        'present_increment',
        'outgoing_packet_manifest',
        'outgoing_master_manifest',
        'delivery_manifest',
        'co_mail',
    ];
}
