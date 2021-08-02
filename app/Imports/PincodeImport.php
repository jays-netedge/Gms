<?php

namespace App\Imports;

use App\Models\GmsPincode;
use Maatwebsite\Excel\Concerns\ToModel;

class PincodeImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new GmsPincode([
            'pincode_value'     => $row[0],                             
            'city_code'    => $row[1], 
            'service'  => $row[2],
            'courier'    => $row[3],
            'gold'    => $row[4],
            'logistics'    => $row[5],
            'regular'    => $row[6],
            'topay'    => $row[7],
            'cod'    => $row[8],
            'branch_id'    => $row[9],
            'pin_status'    => $row[10]
        ]);
    }
}
