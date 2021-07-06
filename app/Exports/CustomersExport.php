<?php

namespace App\Exports;

use App\Models\GmsCustomer;
use App\Models\Admin;
use App\Models\GmsOffice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if (session()->has('session_token')) {
            $adminSession = session()->get('session_token');
            $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
            $office_code = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();
            $CusDetails = GmsCustomer::where('is_deleted', 0)->select(
                'id',
                'cust_code',
                'cust_name',
                'cust_type',
                'cust_city',
                'email_status',
                'sms_status',
                'approved_status')->get();
            return $CusDetails;
        } else {
            return "Session not found.";
        }
    }

    public function headings(): array
    {
        return [
            'SL.NO',
            'CUST CODE',
            'CUST NAME',
            'CUST TYPE',
            'CUST CITY',
            'EMAIL STATUS',
            'SMS STATUS',
            'APPROVED STATUS',
        ];
    }
}
