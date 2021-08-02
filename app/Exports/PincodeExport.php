<?php

namespace App\Exports;

use App\Models\GmsPincode;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\GmsOffice;

class PincodeExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
   

    public function collection()
    {
         if (session()->has('session_token')) {
        $viewPincode = GmsPincode::leftjoin('gms_city', 'gms_pincode.city_code', '=', 'gms_city.city_code')
                                   ->leftjoin('gms_state','gms_city.state_code','=','gms_state.state_code')
                                   ->leftjoin('gms_office AS office','gms_pincode.branch_id','=','office.office_code')
                                   ->leftjoin('admin','office.office_under','=','admin.office_id')
                                    ->select( 
                                        'gms_pincode.id',
                                        'gms_state.state_name',
                                        'gms_state.state_code',
                                        'gms_city.city_name',
                                        'gms_city.city_code',
                                        'gms_pincode.pincode_value',
                                        'gms_pincode.service',
                                        'gms_pincode.courier',
                                        'gms_pincode.gold',
                                        'gms_pincode.logistics',
                                        'gms_pincode.regular',
                                        'gms_pincode.topay',
                                        'gms_pincode.cod',
                                        'gms_pincode.pin_status',
                                        'gms_pincode.branch_id',
                                        'office.office_name',
                                        'admin.username AS ro_code'
                                    )->where('admin.user_type','RO')->get();

                                 
                    return $viewPincode;
       
    }else{
             return "Somthing Error";
        }
 }

    public function headings(): array
    {
       return [

            'SR No',
            'STATE NAME',
            'STATE CODE',
            'CITY NAME',
            'CITY CODE',
            'PINCODE',
            'SERVICE',
            'COURIER',
            'GOLD',
            'LOGISTICS',
            'REGULAR',
            'TOPAY',
            'COD',
            'STATUS',
            'BRANCH ASSIGNED',
            'BRANCH ASSIGNED NAME',
            'RO CODE'
        ];
    }
}
