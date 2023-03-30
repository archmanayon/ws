<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ManualShift;
use App\Models\Punch;
use App\Models\Schedule;
use App\Models\Biometric;
use \Carbon\Carbon;

use Illuminate\Http\Request;

class AbsenceCalendarController extends Controller
{
    public function calendar_absences($collection_of_dates,$searched_user, $holiday) 
    {   
        $mappedArray = collect($collection_of_dates)
        ->map(function ($date) use ($searched_user, $holiday){
        
            $date = Carbon::parse($date);

            $d_date = $date->format('m-d-y');

            $day = $date->format('l');

            $sched_in = $day."_in";

            $sched_out = $day.'_out';

            $official_in = $searched_user->schedule->$sched_in;

            $official_out = $searched_user->schedule->$sched_out;    

            $official_num_hr = round((strtotime($official_out) - 
                strtotime($official_in))/3600,2);                                 
            
            $user_punch_in = $searched_user->punches->where('date', $date->format('mdy'))
                ->pluck('in')->first();

            $user_punch_out = $searched_user->punches->where('date', $date->format('mdy'))
                ->pluck('out')->first(); 
            
            $late = round((strtotime($user_punch_in) - 
                strtotime($official_in))/3600,2);

            $under = round((strtotime($official_out) - 
                 strtotime($user_punch_out))/3600,2);

            if( $searched_user->manual_shift->pluck('date')->contains( $date->format('Y-m-d')))
            {                             
                $Schedule_id =  $searched_user->manual_shift->where('date',$date->format('Y-m-d'))
                                ->pluck('schedule_id')->implode(', ');                                                          
                $official_in = $searched_user->schedule->find($Schedule_id)->Manual_in;
                $official_out = $searched_user->schedule->find($Schedule_id)->Manual_out;
                $official_num_hr = round((strtotime($official_out) - 
                    strtotime($official_in))/3600,2); 

                $late = round((strtotime($user_punch_in) - 
                    strtotime($official_in))/3600,2);

                $under = round((strtotime($official_out) - 
                    strtotime($user_punch_out))/3600,2);
            } 

            $render = round((strtotime($user_punch_out) - strtotime($user_punch_in))/3600,2);
            $tardi = '';

            if ( !$user_punch_in && !$user_punch_out && $late >  $official_num_hr ||$under > $official_num_hr)  
            {
                $type = 'ABS';
                $render = $official_num_hr ;
            }
            elseif($late > 0 && $under > 0)
            {
                $tardi = 'lte_und';
                $type = 'LTE';
                $render = $late;
                
            }
            elseif ($late > 0)
            {
                $type = 'LTE';
                $render = $late;
            }    
            elseif ($under > 0)
            {
                $type = 'UND';
                $render = $under;
            }
            else {
                $type = 'no_tardi';
                $render = $official_num_hr ;
            }

            if(in_array($d_date, $holiday) || $day =='Sunday' || !isset($searched_user->schedule->$sched_in))
                {}  
            
            elseif ($type == 'no_tardi')
                {}

            else {

                return (object) [
                    'student_id'=> $searched_user->student_id,
                    'name'=> $searched_user->name,
                    'date'=> $d_date,
                    'type'=> $type,
                    'rendered'=> $render,
                    'ws_double'=> $tardi == 'lte_und' ? $under : ''
                ];
            }

        })->toArray();

        return $mappedArray;

    }

    public function biometrics($collection_of_dates,$searched_user, $holiday) 
    {          
                       
        $mappedArray = collect($collection_of_dates)
        ->map(function ($date) use ($searched_user, $holiday){            
        
            $date = Carbon::parse($date);

            $d_date = $date->format('m-d-y');

            $day = $date->format('l');

            $sched_in = $day."_in";

            $sched_out = $day.'_out';

            $official_in = $searched_user->schedule->$sched_in;

            $official_out = $searched_user->schedule->$sched_out;    

            $official_num_hr = round((strtotime($official_out) - 
                strtotime($official_in))/3600,2);   
            
            $bio_daily_array = Biometric::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $searched_user->timecard)
                            ->where(DB::raw('SUBSTRING(biotext, 7, 6)'), '=', $date->format('mdy'));                

            $subString_array = $bio_daily_array->selectRaw
            ('                
                SUBSTRING(biotext, 7, 6) AS date_bio,
                SUBSTRING(biotext, 13, 4) AS hour,
                SUBSTRING(biotext, 17, 1) AS in_out
            ')->get();
            
            // $user_punch_in = $searched_user->punches->where('date', $date->format('mdy'))
            //     ->pluck('in')->first();

            // $user_punch_out = $searched_user->punches->where('date', $date->format('mdy'))
            // ->pluck('out')->first();   

            $user_punch_in = $subString_array[0]->hour??0;

            $user_punch_out = $subString_array[1]->hour??0;
                                 
            $late = round((strtotime($user_punch_in) - 
                strtotime($official_in))/3600,2);

            $under = round((strtotime($official_out) - 
                 strtotime($user_punch_out))/3600,2);

                 

            if( $searched_user->manual_shift->pluck('date')->contains( $date->format('Y-m-d')))
            {                             
                $Schedule_id =  $searched_user->manual_shift->where('date',$date->format('Y-m-d'))
                                ->pluck('schedule_id')->implode(', ');                                                          
                $official_in = $searched_user->schedule->find($Schedule_id)->Manual_in;
                $official_out = $searched_user->schedule->find($Schedule_id)->Manual_out;
                $official_num_hr = round((strtotime($official_out) - 
                    strtotime($official_in))/3600,2); 

                $late = round((strtotime($user_punch_in) - 
                    strtotime($official_in))/3600,2);

                $under = round((strtotime($official_out) - 
                    strtotime($user_punch_out))/3600,2);
            } 

            $render = round((strtotime($user_punch_out) - strtotime($user_punch_in))/3600,2);
            $tardi = '';
            
            if ( !$user_punch_in && !$user_punch_out && $late >  $official_num_hr ||$under > $official_num_hr)  
            {
                $type = 'ABS';
                $render = $official_num_hr ;
            }
            elseif($late > 0 && $under > 0)
            {
                $tardi = 'lte_und';
                $type = 'LTE';
                $render = $late;
                
            }
            elseif ($late > 0)
            {
                $type = 'LTE';
                $render = $late;
            }    
            elseif ($under > 0)
            {
                $type = 'UND';
                $render = $under;
            }
            else {
                $type = 'no_tardi';
                $render = $official_num_hr ;
            }

            if(in_array($d_date, $holiday) || $day =='Sunday' || !isset($searched_user->schedule->$sched_in))
                {}  
            
            elseif ($type == 'no_tardi')
                {}

            else {
               
                return (object) [
                    'student_id'=> $searched_user->student_id,
                    'name'=> $searched_user->name,
                    'date'=> $d_date,
                    'type'=> $type,
                    'rendered'=> $render,
                    'ws_double'=> $tardi == 'lte_und' ? $under : '',
                    'bio_daily_array' => $date->format('mdy'),
                    'subString_array' => $subString_array[0]->hour??0
                ];
            }
            
        })->toArray();        
        
        return $mappedArray;
    }

    public function adea_bio($collection_of_dates,$searched_user, $holiday) 
    {          
                       
        $mappedArray = collect($collection_of_dates)
        ->map(function ($date) use ($searched_user, $holiday){            
        
            $date = Carbon::parse($date);

            $d_date = $date->format('m-d-y');

            $day = $date->format('l');

            $am_in = $day."_am_in";

            $am_out = $day."_am_out";

            $pm_in = $day."_pm_in";

            $pm_out = $day."_pm_out";           

            $official_am_in = $searched_user->shift->$am_in??false;

            $official_am_out = $searched_user->shift->$am_out??false;  
            
            $official_pm_in = $searched_user->shift->$pm_in??false;

            $official_pm_out = $searched_user->shift->$pm_out??false;

            $official_am_num_hr = round((strtotime($official_am_out) - 
                strtotime($official_am_in))/3600,2);

             $official_pm_num_hr = round((strtotime($official_pm_out) - 
                strtotime($official_pm_in))/3600,2);             
            
            $bio_daily_array = Biometric::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $searched_user->timecard)
                            ->where(DB::raw('SUBSTRING(biotext, 7, 6)'), '=', $date->format('mdy'));                

            $subString_array = $bio_daily_array->selectRaw
            ('                
                SUBSTRING(biotext, 7, 6) AS date_bio,
                SUBSTRING(biotext, 13, 4) AS hour,
                SUBSTRING(biotext, 17, 1) AS in_out
            ')->get();        
 
            $bio_am_in = $subString_array[0]->hour??false;
            $bio_am_out = $subString_array[1]->hour??false;

            $bio_pm_in = $subString_array[2]->hour ?? ($subString_array[0]->hour??false);
            // $bio_pm_out = $subString_array[3]->hour ?? ($subString_array[2]->hour??($subString_array[1]->hour??false));   
            $bio_pm_out = $subString_array[3]->hour ?? ($subString_array[2]->hour??false?false:
                                                            ($subString_array[1]->hour??false));  
            
            $allowed_for_am = round((strtotime($bio_am_in) - strtotime($official_am_in))/3600,2);
            $allowed_for_pm = round((strtotime($official_pm_in) - strtotime($bio_pm_in))/3600,2);
                      
            $am_punch_in = $allowed_for_am <= 3 ? $bio_am_in : false;

            $am_punch_out =  $allowed_for_am <= 3 ? $bio_am_out : false;
            
            $pm_punch_in = $allowed_for_pm <= 2 ? $bio_pm_in : false;

            $pm_punch_out = $allowed_for_pm <= 2 ? $bio_pm_out : false;
            
            // $ten_min_allowance = round((strtotime('0010'))/3600,2);       
            $ten_min_allowance = 0.17;   
            // $ten_min_allowance = round((strtotime('10'))/3600,2);                           

            $am_late = round((strtotime($am_punch_in)-strtotime($official_am_in))/3600,2);
            $pm_late = $official_pm_in ? 
                round((strtotime($pm_punch_in)-strtotime($official_pm_in))/3600,2) :
                false;
            $late = ($am_late > 0 ? $am_late : 0) + ($pm_late > 0 ? $pm_late : 0) - $ten_min_allowance;

            $am_und = round((strtotime($official_am_out)-strtotime($am_punch_out))/3600,2);
            $pm_und = round((strtotime($official_pm_out)-strtotime($pm_punch_out))/3600,2);
            $under = ( $am_und > 0 ?  $am_und : 0) + ($pm_und > 0 ? $pm_und : 0);


            if( $searched_user->manual_shift->pluck('date')->contains( $date->format('Y-m-d')))
            {                             
                $Schedule_id =  $searched_user->manual_shift->where('date',$date->format('Y-m-d'))
                                ->pluck('schedule_id')->implode(', ');                                                          
                $official_am_in = $searched_user->schedule->find($Schedule_id)->Manual_in;
                $official_am_out = $searched_user->schedule->find($Schedule_id)->Manual_out;
                $official_am_num_hr = round((strtotime($official_am_out) - 
                    strtotime($official_am_in))/3600,2); 

                $am_late = round((strtotime($am_punch_in)-strtotime($official_am_in))/3600,2);
                $pm_late = round((strtotime($pm_punch_in)-strtotime($official_pm_in))/3600,2);
                $late = ($am_late > 0 ? $am_late : 0) + ($pm_late > 0 ? $pm_late : 0) - $ten_min_allowance;

                $under = round((strtotime($official_am_out) - 
                    strtotime($am_punch_out))/3600,2);
            } 

            $am_render = round((strtotime($am_punch_out) - strtotime($am_punch_in))/3600,2);
            $pm_render = round((strtotime($pm_punch_out) - strtotime($pm_punch_in))/3600,2);            
            
            $tardi = '';
            $whole_day = '';
            
            if ( !$am_punch_in || !$am_punch_out || !$pm_punch_in || !$pm_punch_out||
                $am_late >  $official_am_num_hr ||$am_und > $official_am_num_hr ||
                $pm_late >  $official_pm_num_hr ||$pm_und > $official_pm_num_hr 
                
            )  
            {
                $type = 'ABS';
                $am_render = $official_am_num_hr ;
                $whole_day = !$am_punch_in && !$pm_punch_in ? $official_am_num_hr + $official_pm_num_hr : 
                               ( !$am_punch_in || !$am_punch_out ? $official_am_num_hr : 
                                (!$pm_punch_in || !$pm_punch_out? $official_pm_num_hr:''));
            }
            elseif($late > 0 && $under > 0)
            {
                $tardi = 'lte_und';
                $type = 'LTE';
                $am_render = $late;
                
            }
            elseif ($late > 0)
            {
                $type = 'LTE';
                $am_render = $late;
            }    
            elseif ($under > 0)
            {
                $type = 'UND';
                $am_render = $under;
            }
            else {
                $type = 'no_tardi';
                $am_render = $official_am_num_hr ;
            }

            if(in_array($d_date, $holiday) || $day =='Sunday' || !isset($searched_user->shift->$am_in))
                {}  
            
            elseif ($type == 'no_tardi')
                {}

            else {
               
                return (object) [
                    'student_id'=> $searched_user->student_id,
                    'name'=> $searched_user->name,
                    'date'=> $d_date,
                    'type'=> $type,
                    'rendered'=> $am_render,
                    'ws_double'=> $tardi == 'lte_und' ? $under : '',
                    'bio_daily_array' => $date->format('mdy'),
                    'subString_array' =>    $whole_day
                ];
            }
            
        })->toArray();        
        
        return $mappedArray;
    }
   
}
