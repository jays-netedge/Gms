<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Admin extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admin';

    protected $fillable = [
        'name',
        'username',
        'password',
        'security_no',
        'office_id',
        'office_code',
        'city',
        'email',
        'mobile',
        'phone',
        'fax',
        'address',
        'city_name',
        'state',
        'country',
        'pincode',
        'tin_no',
        'serv_no',
        'sac_hsn_code',
        'logo',
        'user_type',
        'status',
        'password_status',
        'company_type',
        'prev_pass_changed',
        'last_pass_changed',
        'last_log_ip',
        'session_time',
        'session_status',
        'last_login_date',
        'last_log_date',
    ];

    public function adminSession()
    {
        return $this->hasMany(AdminSession::class, 'id');
    }

    public function office()
    {
        return $this->belongsTo(GmsOffice::class, 'id');
    }

    public function getlastPassChangedAttribute()
    {
        if (!$this->attributes['last_pass_changed']) {
            return null;
        }
        return Carbon::now()->diffInDays(Carbon::parse($this->attributes['last_pass_changed']));
    }


}
