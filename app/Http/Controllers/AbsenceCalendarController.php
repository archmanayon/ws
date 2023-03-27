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
   
}
