<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsEmp extends Model
{
    use HasFactory;

    protected $table = 'gms_emp';

    protected $fillable = [
        'emp_code',
        'emp_num',
        'emp_name',
        'emp_city',
        'emp_add1',
        'emp_add2',
        'emp_phone',
        'emp_email',
        'emp_sex',
        'emp_bldgrp',
        'emp_dob',
        'emp_education',
        'emp_qualification',
        'emp_doj',
        'emp_dept',
        'emp_dsg',
        'emp_work_type',
        'emp_status',
        'emp_dor',
        'emp_type',
        'emp_rep_offtype',
        'emp_rep_office',
        'emp_rep_office_ro',
        'delivery_code',
        'delivery_branch_status',
        'profile_image',
        'profile_image_small',
        'entry_date',
        'status',
        'user_id',
        'sysid',

    ];

    public function getProfileImageAttribute()
    {
        if (!$this->attributes['profile_image']) {
            return null;
        }
        return 'http://localhost/gms/v1/public/employee/' . $this->attributes['profile_image'];

    }

    public function getProfileImageSmallAttribute()
    {
        if (!$this->attributes['profile_image_small']) {
            return null;
        }
        return 'http://localhost/gms/v1/public/employee/thumbnail/' . $this->attributes['profile_image_small'];

    }
}
