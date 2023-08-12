<?php

namespace App\Exports;

use App\Models\Rawbio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RawbioExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function view(): View
    {
        
        return view('exports.my_dtr_exel', [
            'invoices' => Invoice::all()
        ]);
    }
}
