<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsCnnoReleaseStock extends Model
{
    use HasFactory;

    protected $table = 'gms_cnno_release_stock';

    protected $fillable = [
        'release_stock_cnno',
        'iss_release_id',
   
    ];
}
