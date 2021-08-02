<?php

namespace App\Imports;

use App\Models\GmsCity;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class CityImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    //HeadingRowFormatter::default('none');

    public function collection(Collection $row)
    {
        return $row;
        
    }
}
