<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatAddedFile extends Model
{
    use HasFactory;

    protected $table = 'chat_added_files';

    protected $fillable = [
        'username',
        'file',
    ];
}
