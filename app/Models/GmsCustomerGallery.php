<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsCustomerGallery extends Model
{
    use HasFactory;

    protected $table = 'gms_customer_gallery';

    protected $fillable = [

        'type',
        'cust_code',
        'tittle',
        'description',
        'customer_gal_img',
        'status',
        'cust_ro',
        'created_office',
        'posted_date',

    ];
}
