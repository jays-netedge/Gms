<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsWebCareer extends Model
{
    use HasFactory;

    protected $table = 'gms_web_career';

    protected $fillable = [
        'career_job_interested',
        'career_first_name',
        'career_last_name',
        'career_contact_no',
        'career_exp_years',
        'career_exp_months',
        'career_email_id',
        'career_resume',
        'posted_date',

    ];
}
