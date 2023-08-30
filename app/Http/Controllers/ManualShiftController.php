<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Shift;
use App\Models\Biometric;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Http\Request;

class ManualShiftController extends Controller
{
    public function official_($searched_user, $day, $date)
    {
         // if( $searched_user->manual_shifts->pluck('date')->contains( $date->format('Y-m-d')))
                // {
                //     $Schedule_id =  $searched_user->manual_shifts->where('date',$date->format('Y-m-d'))
                //                     ->pluck('schedule_id')->implode(', ');
                //     $official_am_in = $searched_user->schedule->find($Schedule_id)->Manual_in;
                //     $official_am_out = $searched_user->schedule->find($Schedule_id)->Manual_out;
                //     $official_am_num_hr = round((strtotime($official_am_out) -
                //         strtotime($official_am_in))/3600,2);

                //     $am_late = round((strtotime($punch->am_in)-strtotime($official_am_in))/3600,2);
                //     $pm_late = round((strtotime($punch->pm_in)-strtotime($official_pm_in))/3600,2);
                //     $late = ($am_late > 0 ? $am_late : 0) + ($pm_late > 0 ? $pm_late : 0) - $ten_min_allowance;

                //     $under = round((strtotime($official_am_out) -
                //         strtotime($punch->am_out))/3600,2);
            // }
        $am_in = $day."_am_in";

        $am_out = $day."_am_out";

        $pm_in = $day."_pm_in";

        $pm_out = $day."_pm_out";

        $official_am_in = $searched_user->shift->$am_in??false;
        $official_am_out = $searched_user->shift->$am_out??false;
        $official_pm_in = $searched_user->shift->$pm_in??false;
        $official_pm_out = $searched_user->shift->$pm_out??false;

        $manual_shift_dates = [];

        foreach($searched_user->manual_shifts as $shift){

            $start_shift = $shift->date ?? 0;

            $end_shift = $shift->end_shift?? 0;

            $period = CarbonPeriod::create($start_shift, $end_shift);

            $array_of_dates = $period->toArray();

            foreach($array_of_dates as $each){
                $manual_shift_dates[] = [
                    'shift_date' =>Carbon::parse($each)->format('Y-m-d'),
                    'shift_id' => $shift->shift_id
                ];
            }
        }

        $flattenedDates = array_column($manual_shift_dates, 'shift_date');

        if (in_array($date->format('Y-m-d'), $flattenedDates)){

            $shift_id_no = collect($manual_shift_dates)->where('shift_date', $date->format('Y-m-d'))->pluck('shift_id')->toArray();

            // echo $date->format('Y-m-d').'|'.implode('',$shift_id_no).'<br>';

            $find_shift = Shift::find(implode('', $shift_id_no)) ?? false;

            $official_am_in     = $find_shift->$am_in ?? false;
            $official_am_out    = $find_shift->$am_out ?? false;
            $official_pm_in     = $find_shift->$pm_in ?? false;
            $official_pm_out    = $find_shift->$pm_out ?? false;

        }
        // else{
        //     echo $date->format('Y-m-d') .'|'.'<br>';
        // }

        // if( $searched_user->manual_shifts->pluck('date')->contains( $date->format('Y-m-d')))

        // {
        //     $shift_id =  $searched_user->manual_shifts->where('date',$date->format('Y-m-d'))
        //                     ->pluck('shift_id')->implode(', ');


        //     $official_am_in     = $searched_user->shift->find($shift_id)->am_in??false;
        //     $official_am_out    = $searched_user->shift->find($shift_id)->am_out??false;
        //     $official_pm_in     = $searched_user->shift->find($shift_id)->pm_in??false;
        //     $official_pm_out    = $searched_user->shift->find($shift_id)->pm_out??false;
        // }

        return (object) [
            'am_in'     => $official_am_in ?? false,

            'am_out'    => $official_am_out ?? false,

            'pm_in'     => $official_pm_in ?? false,

            'pm_out'    => $official_pm_out ?? false,

            'am_num_hr' => round((strtotime($official_am_out) -
                            strtotime($official_am_in))/3600,2),

            'pm_num_hr' => round((strtotime($official_pm_out) -
                            strtotime($official_pm_in))/3600,2)

        ];

    }
}
