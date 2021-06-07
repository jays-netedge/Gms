<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsRateServiceWalkinCustomer extends Model
{
    use HasFactory;

    protected $table = 'gms_rate_service_walkincustomer';

    protected $fillable = [
          'service_type',
          'oda_type',
          'min_weight',
    		  'max_weight',
    		  'percentage',
    		  'amount',

 
     ];
}
