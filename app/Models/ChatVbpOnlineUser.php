<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatVbpOnlineUser extends Model
{
    use HasFactory;

    protected $table = 'chat_vpb_online_users';

    protected $fillable = [
        'username',
    ];
}
