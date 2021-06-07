<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsTaxType extends Model
{
    use HasFactory;

    protected $table = 'gms_tax_type';

    protected $fillable = [
        'tax_name',
        'rate',
        'from_date',
        'to_date',
        'status',

    ];
}
