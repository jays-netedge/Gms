<?php

namespace App\Imports;

use App\Models\GmsPmfDtls;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CnnoUpdateImport implements ToCollection 
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection  $rows)
    {
        return $rows;
    }
}
