<?php

namespace App\Exports;

use App\Models\GmsCountries;
use App\Models\Admin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CountryExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();

        $gmsCountry = GmsCountries::where('is_deleted', 0)->select('countries_iso_code_2', 'countries_name')->get();
        return $this->successResponse(self::CODE_OK, $gmsCountry);

    }

    public function headings(): array
    {
        return [
            'COUNTRY CODE',
            'COUNTRY NAME',
        ];
    }
}
