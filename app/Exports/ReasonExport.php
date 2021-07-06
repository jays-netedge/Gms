<?php

namespace App\Exports;

use App\Models\GmsNdelReason;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReasonExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $getNonDeliveryData = GmsNdelReason::select('id', 'ndel_code as value', 'ndel_desc as label')->where('is_deleted', 0);
        $getExportData = $getNonDeliveryData->get();
        return $this->successResponse(self::CODE_OK, $getExportData);
    }

    public function headings(): array
    {
        return [
            'SL.NO',
            'REASON CODE',
            'REASON DESCRIPTION'
        ];
    }
}
