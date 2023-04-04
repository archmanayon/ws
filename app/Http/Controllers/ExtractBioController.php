<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Shift;
use App\Models\Biometric;
use \Carbon\Carbon;

use Illuminate\Http\Request;

class ExtractBioController extends Controller
{
    public function extract_bio ($searched_user, $date, $official_am_in, $official_pm_in){
        
        $bio_daily_array = Biometric::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $searched_user->timecard)
                        ->where(DB::raw('SUBSTRING(biotext, 7, 6)'), '=', $date->format('mdy'));                

        $all_bio_punches = $bio_daily_array->selectRaw
            ('                
                SUBSTRING(biotext, 7, 6) AS date_bio,
                SUBSTRING(biotext, 13, 4) AS hour,
                SUBSTRING(biotext, 17, 1) AS in_out
                ')
        ->get();        

        $bio_am_in = $all_bio_punches[0]->hour??false;
        $bio_am_out = $all_bio_punches[1]->hour??false;

        $bio_pm_in = $all_bio_punches[2]->hour ??  $bio_am_in;            
        $bio_pm_out = $all_bio_punches[3]->hour ?? ($all_bio_punches[2]->hour??false?false:
                                                    ($all_bio_punches[1]->hour??false));  
        
        $allowance_for_am = round((strtotime($bio_am_in) - strtotime($official_am_in))/3600,2);
        $allowance_for_pm = round((strtotime($official_pm_in) - strtotime($bio_pm_in))/3600,2);  
        
        $am_in = $allowance_for_am <= 3 ? $bio_am_in : false;

        $am_out =  $allowance_for_am <= 3 ? $bio_am_out : false;
        
        $pm_in = $allowance_for_pm <= 2 ? $bio_pm_in : false;

        $pm_out = $allowance_for_pm <= 2 ? $bio_pm_out : false;
        
        $am_render = round((strtotime($am_out) - strtotime($am_in))/3600,2) < 0 ? false : 
            round((strtotime($am_out) - strtotime($am_in))/3600,2);

        $pm_render = round((strtotime($pm_out) - strtotime($pm_in))/3600,2) < 0 ? false:
            round((strtotime($pm_out) - strtotime($pm_in))/3600,2);           
        
        return (object) [
            'am_in' => $allowance_for_am <= 3 ? $bio_am_in : false,

            'am_out' =>  $allowance_for_am <= 3 ? $bio_am_out : false,
            
            'pm_in' => $allowance_for_pm <= 2 ? $bio_pm_in : false,
    
            'pm_out' => $allowance_for_pm <= 2 ? $bio_pm_out : false,
            
            'all_bio_punches' => $all_bio_punches,

            'am_render' =>  $am_render,

            'pm_render' =>  $pm_render
        ];
    }

    public function extract_tardi($searched_user, $day, $bio_punch, $ten_min_allowance)
    {
        $am_in = $day."_am_in";

        $am_out = $day."_am_out";

        $pm_in = $day."_pm_in";

        $pm_out = $day."_pm_out";      

        $official_am_in = $searched_user->shift->$am_in??false;

        $official_am_out = $searched_user->shift->$am_out??false;  
        
        $official_pm_in = $searched_user->shift->$pm_in??false;

        $official_pm_out = $searched_user->shift->$pm_out??false;

        $am_late = $official_am_in && $bio_punch->am_in < $official_am_out? 
            round((strtotime($bio_punch->am_in)-strtotime($official_am_in))/3600,2) :
            false;
        $pm_late = $official_pm_in && $bio_punch->pm_in < $official_pm_out? 
            round((strtotime($bio_punch->pm_in)-strtotime($official_pm_in))/3600,2) :
            false;
        $late = ($am_late > 0 ? $am_late : 0) + ($pm_late > 0 ? $pm_late : 0) 
            - $ten_min_allowance;

        $am_und = $official_am_out &&  $bio_punch->am_out > $official_am_in ? 
            round((strtotime($official_am_out)-strtotime($bio_punch->am_out))/3600,2) :
            false;
        $pm_und = $official_pm_out &&  $bio_punch->pm_out > $official_am_in? 
            round((strtotime($official_pm_out)-strtotime($bio_punch->pm_out))/3600,2) :
            false;
        $under = ( $am_und > 0 ?  $am_und : 0) + ($pm_und > 0 ? $pm_und : 0);

        return (object) [
            "am_late"   => $am_late,
            "pm_late"   => $pm_late,
            "late"      => $late,
            "am_und"    => $am_und,
            "pm_und"    => $pm_und,
            "under"     => $under
            
        ];
 
    }
}
