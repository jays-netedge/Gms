<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chat';

    protected $fillable = [
        'to',
        'from',
        'message',
        'time',
        'sender_read',
        'receiver_read',
        'sender_deleted',
        'receiver_deleted',
        'file',
    ];
}
