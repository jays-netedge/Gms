<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsAlertConfig extends Model
{
    use HasFactory;

    protected $table = 'gms_alerts_config';

    protected $fillable = [
        'type',
        'display',
        'message',
    ];
}
