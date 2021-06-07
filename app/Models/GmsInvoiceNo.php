<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsInvoiceNo extends Model
{
    use HasFactory;

    protected $table = 'gms_invoice_no';

    protected $fillable = [
        'branch_ro',
        'month',
        'year',
        'invoice_no',


    ];
}
