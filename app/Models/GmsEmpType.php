<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsEmpType extends Model
{
    use HasFactory;

    protected $table = 'gms_emp_type';

    protected $fillable = [
        'emp_type_code',
        'emp_type_name',
        'status',
        'empentry_date_city',
        'user_id',
        'sysid',
    ];
}
