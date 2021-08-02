<?php

namespace App\Exports;

use App\Models\GmsDept;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DesignationExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
         if (session()->has('session_token')) {
            $adminSession = session()->get('session_token');
          
            $gmsDept = GmsDesg::where('is_deleted',0)->where('status','A')->where('user_id',$adminSession->admin_id)->select('id','desg_code','desg_name')->get();
            return  $gmsDept;
          } else {
            return "Session not found.";

        }
    }

    public function headings(): array
    {
        return [
            'SR No',
            'DESIGNATION CODE',
            'DESIGNATION NAME'
            
        ];
    }
}
