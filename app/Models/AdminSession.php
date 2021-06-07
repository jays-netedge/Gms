<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSession extends Model
{
    use HasFactory;

    protected $table = 'admin_session';

    protected $fillable = [
        'admin_id',
        'user_agent',
        'ip_address',
        'session_token',
        'is_active',
    ];

    public function admin(){

    	return $this->belongsTo(Admin::class, 'admin_id');
    }

    
}
