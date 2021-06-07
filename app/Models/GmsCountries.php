<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsCountries extends Model
{
    use HasFactory;

    protected $table = 'gms_countries';

    protected $fillable = [
        'countries_name',
        'countries_iso_code_2',
        'countries_iso_code_3',
        'address_format_id',
        'status',
        'entry_date',
        'user_id',

    ];


}
