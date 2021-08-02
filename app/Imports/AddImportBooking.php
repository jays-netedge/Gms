<?php

namespace App\Imports;


use App\Models\GmsBookingDtls;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;


class AddImportBooking implements ToModel
{
    /**
    * param array $row
    *
    * return \Illuminate\Database\Eloquent\Model|null
    */

    HeadingRowFormatter::default('none');

    public function model(array  $row)
    {
        return new GmsBookingDtls([
           'book_pin'     => $row['DESTPIN'],                             
            'book_dest'    => $row['DESTCITY'], 
            'book_location'  => $row[2],
            'book_cnno'    => $row[3],
            'book_refno'    => $row[4],
            'book_cons_addr'    => $row[5],
            'book_weight'    => $row[6],
            'book_pcs'    => $row[7],
            'book_vol_weight'    => $row[8],
            'book_vol_lenght'    => $row[9],
            'book_vol_breight'    => $row[10],
            'book_vol_height'    => $row[11],
            'book_product_type'    => $row[12],
            'book_doc'    => $row[13],
            'book_mode'    => $row[14],
            'book_topay'    => $row[15],
            'book_cod'    => $row[16],
            'book_mps_rate'    => $row[17],
            'book_fov_rate'    => $row[18],
           // 'book_fvr_rate'    => $row[20],
            'book_isc_rate'    => $row[20],
            'book_remarks'    => $row[21],
            'book_cn_name'    => $row[22],
            'book_cn_mobile'  => $row[23]
            // 'book_cons_dtl'  => $row[25],
            // 'book_cons_mobile'    => $row[26
        ]);

    	 

    }
}