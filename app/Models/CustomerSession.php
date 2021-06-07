<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSession extends Model
{
    use HasFactory;

    protected $table = 'customer_session';

    protected $fillable = [
        'customer_id',
        'user_agent',
        'ip_address',
        'session_token',
    ];
}
