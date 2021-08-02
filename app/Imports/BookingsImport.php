<?php

namespace App\Imports;

use App\Models\GmsBookingDtls;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class BookingsImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $row)
    {

        return $row;
        // return new GmsBookingDtls([
        //     'book_pin'     => $row[1],                             
        //     'book_dest'    => $row[2], 
        //     'book_location'  => $row[3],
        //     'book_cnno'    => $row[4],
        //     'book_refno'    => $row[5],
        //     'book_cons_addr'    => $row[6],
        //     'book_weight'    => $row[7],
        //     'book_pcs'    => $row[8],
        //     'book_vol_weight'    => $row[9],
        //     'book_vol_lenght'    => $row[10],
        //     'book_vol_breight'    => $row[11],
        //     'book_vol_height'    => $row[12],
        //     'book_product_type'    => $row[13],
        //     'book_doc'    => $row[14],
        //     'book_mode'    => $row[15],
        //     'book_topay'    => $row[16],
        //     'book_cod'    => $row[17],
        //     'book_mps_rate'    => $row[18],
        //     'book_fov_rate'    => $row[19],
          
        //     'book_isc_rate'    => $row[20],
        //     'book_remarks'    => $row[21],
        //     'book_cn_name'    => $row[22],
        //     'book_cn_mobile'  => $row[23]
          
        // ]);
    }
}
