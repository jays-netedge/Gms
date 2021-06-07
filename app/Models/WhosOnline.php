<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhosOnline extends Model
{
    use HasFactory;

    protected $table = 'whos_online';

    protected $fillable = [
        'customer_id',
        'full_name',
        'session_id',
        'ip_address',
        'time_entry',
        'time_last_click',
        'last_page_url',


    ];
}
