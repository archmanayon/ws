<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller{

    public function print_absences(User $employee) {
         $holiday = array("01-01-23", "01-02-23","01-03-23",
                            "01-04-23","01-05-23","01-06-23",
                            "01-07-23","01-16-23",
                            "02-24-23", "02-25-23");

        $thirty_days = array('04', '06', '09','11');

        $thirty_one = array('01', '03','05', '07', '08','10', '12');

        $manual_shift;         

        $month_ = request('month');

        $year_ = request('year');

        // $num_days = in_array($month_, $thirty_days) ? '30':
        // (in_array($month_, $thirty_one)? '31':
        //     ($month_ =='0'? '0':'28')
        // );

        $create_num_days = Carbon::createFromDate($year, $month, 1);
        $num_days = $create_num_days->daysInMonth;


       return view ('records.print',[
       ]);
   }
}
