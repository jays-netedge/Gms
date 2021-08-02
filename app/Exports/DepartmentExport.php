<?php

namespace App\Exports;

use App\Models\GmsDept;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DepartmentExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
         if (session()->has('session_token')) {
            $adminSession = session()->get('session_token');
          
            $gmsDept = GmsDept::where('is_deleted',0)->where('status','A')->where('user_id',$adminSession->admin_id)->select('id','dept_code','dept_name')->get();
            return  $gmsDept;
          } else {
            return "Session not found.";

        }
    }

    public function headings(): array
    {
        return [
            'SR No',
            'DEPARTMENT CODE',
            'DEPARTMENT NAME'
            
        ];
    }
}
