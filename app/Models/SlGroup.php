<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlGroup extends Model
{
    use HasFactory;

    protected $table = 'sl_groups';

    protected $fillable = [

        'name',
        'status',


        ];
}
