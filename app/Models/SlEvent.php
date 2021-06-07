<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlEvent extends Model
{
    use HasFactory;

    protected $table = 'sl_events';

    protected $fillable = [
        'title',
        'fromDate',
        'toDate',
        'addDate',
        'keywords',
        'description',
        'contact',
        'email',
        'en_comments',
        'finish',
        'comments',
        'is_archived',
        'rss_date',


    ];
}
