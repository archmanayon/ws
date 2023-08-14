<?php

namespace App\Exports;

use App\Models\Rawbio;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\Models\User;
use App\Models\ManualShift;
use App\Models\Punch;
use App\Models\Shift;
use App\Models\Update_bio;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Http\Controllers\BiometricController;

class RawbioExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $selected_dates;

    public function __construct($selected_dates)
    {
        $this->selected_dates = $selected_dates;
    }  

    public function dtr_exel()
    {
        $searched_user = auth()->user()??false;

        $holiday = array(
            "01-05-23", "01-06-23",
            "02-24-23", "02-25-23",
            "04-06-23", "04-07-23",
            "04-08-23", "04-10-23", "05-01-23",
            "04-21-23", "06-12-23", "06-28-23"
        );

        $split = explode('to', $this->selected_dates);
        $start_date = $split[0] ?? 0;
        $end_date = $split[1] ?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection_of_dates = collect($dates);

        return $mappedArray = app()->call(
            BiometricController::class . '@text_files_part_2',
            [
                'collection_of_dates' => $collection_of_dates,
                'searched_user' => $searched_user,
                'holiday' => $holiday
            ]
        );          


    }

    public function view(): View
    {
        // dd('gareturn from rawbioexport. awa beh');
        
        return view('exports.exel'
            , [
                'mapped_days'   =>  $this->dtr_exel(),
            ]
        );
    }
}
