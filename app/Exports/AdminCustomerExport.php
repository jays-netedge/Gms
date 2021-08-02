<?php

namespace App\Exports;

use App\Models\GmsCustomer;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AdminCustomerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
        $CusDetails = GmsCustomer::where('is_deleted', 0)->select(
                'id',
                'cust_type',
                'cust_code',
                'cust_la_ent',
                'cust_account_type',
                'cust_la_address',
                'cust_la_pan',
                'cust_la_servicetax',
                'cust_la_cin',
                'cust_la_cindate',
                'cust_name',
                'cust_cd_contract_date',
                'cust_cd_renewal_date',
                'cust_cd_exp_date',
                'cust_cd_remarks',
                'cust_sd_fixed',
                'cust_secdip_paid',
                'cust_pb_nature',
                'cust_pb_empdeployed',
                'cust_pb_vehdeployed',
                'cust_pb_turnover',
                'cust_ad_bank_name',
                'cust_ad_bank_branch',
                'cust_ad_account_no',
                'cust_ad_ifsc_code',
                'cust_br_name',
                'cust_br_name1',
                'cust_rep_office',
                'approved_status')->orderBy('id','ASC');

            $getExportData = $CusDetails->get();
            return $getExportData;
    }

    public function headings(): array
    {
        return [
        	
            'SL.NO',
            'OFFICE TYPE',
            'OFFICE CODE',
            'ENTERPRISE',
            'ACCOUNT TYPE',
            'BUSINESS ADDRESS',
            'PAN',
            'SERVICE TAX',
            'CIN',
            'CIN DATE',
            'PERSON NAME',
            'CONTRACT DATE',
            'RNEWAL DATE',
            'EXPIRE DATE',
            'REMARK',
            'SEC.DIP.FIXED',
            'SEC.DIP.PAID',
            'NATURE OF PRESENT BUSINESS',
            'NUMBER OF EMPLOYEES DEPLOYED',
            'NUMBER OF VEHICLES DEPLOYED',
            'PRESENT TURNOVER',
            'NAME OF THE BANK',
            'NAME OF THE BRANCH',
            'ACCOUNT NUMBER',
            'IFSC CODE',
            'NAME REFERENCED1',
            'NAME REFERENCED2',
            'REPORTING OFFICE',
            'APPROVED STATUS'
        ];
    }
}
