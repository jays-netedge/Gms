<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsNews extends Model
{
    use HasFactory;

    protected $table = 'gms_news';

    protected $fillable = [
        'title',
        'description',
        'image',
        'type',
        'status',
        'posted_date',

    ];
}
