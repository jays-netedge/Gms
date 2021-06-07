<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GmsInvoiceEmp extends Model
{
    use HasFactory;

    protected $table = 'gms_invoice_emp';

    protected $fillable = [
        'invoice_no',
        'del_agent_code',
        'del_agent_type',
        'del_code',
        'total_cnno',
        'total',

    ];
}
