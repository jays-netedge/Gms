<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCatType extends Model
{
    use HasFactory;

    protected $table = 'gms_book_cat_type';

    protected $fillable = [
        'id',
        'book_type',
        'is_deleted',
       
    ];
}
