<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table1 extends Model
{
    use HasFactory;

    protected $table = 'table1';

    protected $fillable = [
        'column1',
        'column2',
        'column3',
        'column4',
        'column5',


    ];
}
