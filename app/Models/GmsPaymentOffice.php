<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsPaymentOffice extends Model
{
    use HasFactory;

    protected $table = 'gms_payment_office';

    protected $fillable = [
        'type',
        'invoice_receipt',
        'reference_id',
        'office_code',
        'office_type',
        'amount',
        'paid_through',
        'bank_name',
        'check_no',
        'check_date',
        'deposit_DD',
        'date',
        'description',
        'status',
        'posted_date',


    ];
}
