<?php

namespace App\Exports;

use App\Models\GmsPincode;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PincodeExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $viewPincode = GmsPincode::join('gms_city', 'gms_pincode.city_code', '=', 'gms_city.city_code')
            ->select(DB::raw('concat(gms_city.city_code,"(",gms_city.city_name,")")As city'),
                'gms_pincode.pincode_value',
                'gms_pincode.service',
                'gms_pincode.courier',
                'gms_pincode.gold',
                'gms_pincode.logistics',
                'gms_pincode.regular',
                'gms_pincode.topay',
                'gms_pincode.cod',
                'gms_pincode.branch_id',
                'gms_pincode.pin_status'
            );
        $getPincodeData = $viewPincode->get();
        return $this->successResponse(self::CODE_OK, $getPincodeData);
    }

    public function headings(): array
    {
        return [
            'SR No',
            'CITY',
            'PINCODE',
            'SERVICE',
            'COURIER',
            'GOLD',
            'LOGISTICS',
            'REGULAR',
            'TOPAY',
            'COD',
            'BRANCH',
            'STATUS'
        ];
    }
}
