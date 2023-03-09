<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ManualShift;
use App\Models\Punch;
use App\Models\Schedule;

use Illuminate\Http\Request;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;

class ScheduleController extends Controller{

    public function print_absences() {

        $holiday = array("01-01-23", "01-02-23","01-03-23",
                            "01-04-23","01-05-23","01-06-23",
                            "01-07-23","01-16-23",
                            "02-24-23", "02-25-23");
        $manual_shift;         

        // $month = request('month');

        // $year = request('year');

        $month = 2;

        $year = 2023;

        // $num_days = in_array($month_, $thirty_days) ? '30':
        // (in_array($month_, $thirty_one)? '31':
        //     ($month_ =='0'? '0':'28')
        // );
        
        $start_date = '2023-02-01';
        $end_date = '2023-02-28';

        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();

       return view ('print',[

        'num_days' => $count_dates,
        'holiday' => $holiday,
        'collection_of_dates' => $collection,
        'searched_user' => User::find(9),
        'user' => User::all(),
        'punches' => Punch::all(),
        'schedule' => Schedule::find(5),
        'manual_shift' => ManualShift::all()

       ]);
   }
}
