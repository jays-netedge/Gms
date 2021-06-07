<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsConfiguration extends Model
{
    use HasFactory;

    protected $table = 'gms_configuration';

    protected $fillable = [
        'configuration_tittle',
        'configuration_egg',
        'configuration_key',
        'configuration_value',
        'sort_order',
   
    ];
}
