<?php

namespace App\Exports;

use App\Models\GmsCity;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CityExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $gmsCity = GmsCity::join('gms_state', 'gms_city.state_code', '=', 'gms_state.state_code')->select('gms_city.id',
            DB::raw('concat(gms_state.state_name,"(",gms_state.state_code,")")As state_name'),
            'gms_city.city_code',
            'gms_city.city_name',
            'gms_city.metro'
        );
        $getExportData = $gmsCity->get();
        return $this->successResponse(self::CODE_OK, $getExportData);
    }

    public function headings(): array
    {
        return [
            'SR No',
            'STATE NAME',
            'CITY CODE',
            'CITY NAME',
            'METRO',
            'REPORTING CITY CODE'
        ];
    }
}
