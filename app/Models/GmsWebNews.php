<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsWebNews extends Model
{
    use HasFactory;

    protected $table = 'gms_web_news';

    protected $fillable = [
        'title',
        'description',
        'image',
        'type',
        'status',
        'posted_date',

    ];
}
