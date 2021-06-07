<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GmsBookingDtls;
use App\Models\GmsCustomer;
use App\Models\GmsPmfDtls;

class DashBoardController extends Controller
{
    protected $request;
   

    public function __construct(Request $request)
    {
        $this->request = $request;
        
    }

    

    public function totalCount()
    {
       $data = [
        'todayBooking' => \App\Models\GmsBookingDtls::whereDay('created_at', now()->day)->count(),
        'totalCustomer' => \App\Models\GmsCustomer::where('is_deleted', 0)->count(),
        'totalIncoming' => \App\Models\GmsPmfDtls::whereDay('created_at', now()->day)->count(),
        'totalOutGoing' => \App\Models\GmsPmfDtls::whereDay('created_at', now()->day)->count(),
    ];

       return $data;
    }

    
}
