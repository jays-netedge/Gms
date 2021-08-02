<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GmsBookingDtls;
use App\Models\GmsCustomer;
use App\Models\GmsPmfDtls;
use App\Models\Admin;
use App\Models\GmsOffice;

class DashBoardController extends Controller
{
    protected $request;
   

    public function __construct(Request $request)
    {
        $this->request = $request;
        
    }

    public function totalCount()
    {

        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
        $office_code = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

       $data = [
        'todayBooking' => \App\Models\GmsBookingDtls::where('book_br_code', $office_code->office_code)->whereDay('book_mfdate', now()->day)->count(),
        'totalCustomer' => \App\Models\GmsCustomer::where('created_office_code',$office_code->office_code)->where('is_deleted', 0)->count(),
        'totalIncoming' => \App\Models\GmsPmfDtls::where('pmf_type','IPMF')->where('pmf_origin',$office_code->office_code)->whereDay('pmf_date', now()->day)->count(),
        'totalOutGoing' => \App\Models\GmsPmfDtls::where('pmf_type','OPMF')->where('pmf_origin',$office_code->office_code)->whereDay('pmf_date', now()->day)->count(),
    ];

       return $data;
    }

    
}
