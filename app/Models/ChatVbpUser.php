<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatVbpUser extends Model
{
    use HasFactory;

    protected $table = 'chat_vpb_users';

    protected $fillable = [
        'fullname',
        'username',
        'password',
        'photo',
    ];
}
