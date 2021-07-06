<?php

namespace App\Exports;

use App\Models\GmsState;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StateExport implements FromCollection, WithHeadings
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $getCountryStateZone = GmsState::select(
            'gms_state.id',
            DB::raw('CONCAT(gms_countries.countries_name,"(",gms_countries.countries_iso_code_2,")") As country'),
            DB::raw('CONCAT(gms_zone.zone_name,"(",gms_zone.zone_code,")") As zone'),
            'gms_state.state_code',
            'gms_state.state_name',
        );
        $getCountryStateZone->join('gms_countries', 'gms_state.country_id', '=', 'gms_countries.id');
        $getCountryStateZone->join('gms_zone', 'gms_state.zone_id', '=', 'gms_zone.id');
        $dataState = $getCountryStateZone->get();
        return $this->successResponse(self::CODE_OK, $dataState);
    }

    public function headings(): array
    {
        return [
            'SR No',
            'COUNTRY',
            'ZONE',
            'STATE CODE',
            'STATE NAME'
        ];
    }
}
