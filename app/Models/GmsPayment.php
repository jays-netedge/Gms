<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsPayment extends Model
{
    use HasFactory;

    protected $table = 'gms_payment';

    protected $fillable = [
        'type',
        'invoice_receipt',
        'reference_id',
        'cust_code',
        'amount',
        'paid_through',
        'bank_name',
        'check_no',
        'check_date',
        'deposit_DD',
        'date',
        'description',
        'status',
        'cust_ro',
        'created_office',
        'posted_date',


    ];

    public function getPostedDateAttribute($value)
    {
        if (!$this->attributes['posted_date']) {
            return null;
        }

        return Carbon::parse($this->attributes['posted_date'])->format('d/m/Y');
    }

    public function getDepositDDAttribute()
    {
        if (!$this->attributes['deposit_DD']) {
            return null;
        }
        
        return URL::to('/').'/public/adminPayment/'.$this->attributes['deposit_DD'];

    }
}
