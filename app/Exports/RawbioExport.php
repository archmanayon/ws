<?php

namespace App\Exports;

use App\Models\Rawbio;
use Maatwebsite\Excel\Concerns\FromCollection;

class RawbioExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Rawbio::all();
    }
}
