<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsBookCategory extends Model
{
    use HasFactory;

    protected $table = 'gms_book_category';

    protected $fillable = [
        'book_cat_name',
        'book_cat_type',
        'book_cat_status',

  ];
}
