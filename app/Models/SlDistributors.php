<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlDistributors extends Model
{
    use HasFactory;

    protected $table = 'sl_distributors';

    protected $fillable = [
        'company_name',
        'name',
        'city',
        'mobile',
        'email',
        'website',
        'type',
        'posted_date',


    ];
}
