<?php

namespace App\Exports;

use App\Models\GmsOffice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OfficeExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $officeList = GmsOffice::where('is_deleted', 0)->select('id', 'office_code', 'office_name', 'office_type', 'office_under', 'office_ent', 'office_add1', 'office_add2', 'office_city', 'office_pin', 'office_location', 'office_phone', 'office_fax', 'office_email', 'office_contact', 'office_contract_date', 'office_renewal_date', 'office_exp_date', 'office_pan', 'office_stax_no', 'status');
        $getExportData = $officeList->get();
        return $this->successResponse(self::CODE_OK, $getExportData);
    }

    public function headings(): array
    {
        return [

            'SR No',
            'OFFICE CODE',
            'OFFICE NAME',
            'OFFICE TYPE',
            'OFFICE UNDER',
            'ENTERPRISE',
            'ADDRESS_1',
            'ADDRESS_2',
            'CITY',
            'PINCODE',
            'LOCAION',
            'PHONE',
            'FAX',
            'EMAIL',
            'PERSON',
            'CONTARCT DATE',
            'RENEWAL DATE',
            'EXPIRE DATE',
            'PAN NO',
            'SERVICE TAC NO',
            'STATUS'
        ];
    }
}
