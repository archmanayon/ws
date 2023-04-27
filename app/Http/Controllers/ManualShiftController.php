<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Shift;
use App\Models\Biometric;
use \Carbon\Carbon;

use Illuminate\Http\Request;

class ManualShiftController extends Controller
{
    public function official_($searched_user, $day, $date)
    {
         // if( $searched_user->manual_shift->pluck('date')->contains( $date->format('Y-m-d')))
                // {                             
                //     $Schedule_id =  $searched_user->manual_shift->where('date',$date->format('Y-m-d'))
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

        if( $searched_user->manual_shift->pluck('date')->contains( $date->format('Y-m-d')))
        
        {                             
            $shift_id =  $searched_user->manual_shift->where('date',$date->format('Y-m-d'))
                            ->pluck('shift_id')->implode(', ');                                                          
            $official_am_in     = $searched_user->shift->find($shift_id)->Manual_am_in??false;
            $official_am_out    = $searched_user->shift->find($shift_id)->Manual_am_out??false;
            $official_pm_in     = $searched_user->shift->find($shift_id)->Manual_pm_in??false;
            $official_pm_out    = $searched_user->shift->find($shift_id)->Manual_pm_out??false;
        } 

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
