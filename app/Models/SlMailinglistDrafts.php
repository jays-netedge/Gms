<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlMailinglistDrafts extends Model
{
    use HasFactory;

    protected $table = 'sl_mailinglist_drafts';

    protected $fillable = [
        'subject',
        'message',
        'texthtml',
        'lastsaved',


    ];
}
