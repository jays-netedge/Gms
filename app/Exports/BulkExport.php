<?php

namespace App\Exports;

use App\Models\Admin;
use App\Models\GmsEmp;
use App\Models\GmsOffice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BulkExport implements FromCollection, WithHeadings
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
            $empDetails = GmsEmp::where('emp_rep_office', $office_code->office_code)->select(
                'id',
                'emp_code',
                'emp_name',
                'emp_add1',
                'emp_add2',
                'emp_phone',
                'emp_email',
                'emp_sex',
                'emp_bldgrp',
                'emp_dob',
                'emp_doj',
                'emp_dept',
                'emp_dsg',
                'emp_status',
                'emp_dor',
                'emp_rep_offtype',
                'emp_rep_office',)->where('is_deleted', 0)->get();

            return $empDetails;
        } else {
            return "Session not found.";

        }
    }

    public function headings(): array
    {
        return [
            'SL.NO',
            'OFFICE CODE',
            'OFFICE NAME',
            'ADDRESS1',
            'ADDRESS2',
            'PHONE',
            'EMAIL',
            'SEX',
            'BLOOD GROUP',
            'DATE OF BIRTH',
            'DATE OF JOINING',
            'DEPARTMENT',
            'DESIGNATION',
            'STATUS',
            'DATE OF RESIGNATION',
            'REP.OFFICE Type',
            'REPORTING OFFICE',
        ];
    }
}
