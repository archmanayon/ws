<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ManualShift;
use App\Models\Shift;
use App\Models\Punch;
use App\Models\Schedule;
use App\Models\Biometric;
use App\Models\Rawbio;
use Carbon\Carbon;

use Illuminate\Http\Request;

class AbsenceCalendarController extends Controller
{
    // use this ___________________
    public function adea_bio($collection_of_dates, $searched_user, $holiday) 
    {          
        $late_count = 0;

        // ----------------orig bio----------------------------------
        $orig_bio = Rawbio::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $searched_user->timecard??false);
        $sub_orig_bio = $orig_bio->selectRaw('
            SUBSTRING(biotext, 7, 6) AS date,
            SUBSTRING(biotext, 13, 4) AS hour,
            SUBSTRING(biotext, 17, 1) AS in_out,
            SUBSTRING(biotext, 1, 17) AS biotext,
            SUBSTRING(punchtype_id, 1,1) AS punchtype_id
        ');

         // querry fron shcp bio
         if($searched_user ){
            
            $sub_shcp_punch =  $searched_user->punches()->select('date', 'hour', 'in_out', 'biotext', 'punchtype_id'); 

            $merged = $sub_orig_bio->union($sub_shcp_punch)->get()->sortBy('biotext');  

         } else{

            $merged = $sub_orig_bio->get()->sortBy('biotext');  
         }         

        $mappedArray = $collection_of_dates
        ->filter(function ($each_day) use ($holiday) {
            // Define your condition here            

            $date = Carbon::parse($each_day);

            $d_date = $date->format('m-d-y');

            $day = $date->format('l');   
            
            $am_in = $day."_am_in";
            
            $pm_in = $day."_pm_in";           

            return !in_array($d_date, $holiday) && $day !=='Sunday' ||
            isset($searched_user->shift->$am_in) && isset($searched_user->shift->$pm_in);

        })

        ->map(function ($date) use ($searched_user, $holiday, &$late_count, $merged){            
        
            $date = Carbon::parse($date);

            $d_date = $date->format('m-d-y');

            $day = $date->format('l');

            $am_in = $day."_am_in";
            
            $pm_in = $day."_pm_in";
                                      
            $ten_min_allowance = 0.17;
            // $ten_min_allowance = 0;           

                //---to choose between 'official shift' and 'manual shift'
            $official = app()->call(ManualShiftController::class.'@official_',
            [
                'searched_user'     =>  $searched_user,
                'date'              =>  $date,
                'day'               =>  $day
            ]);

                //---to extract punch 'object' from bio text files
            $punch = app()->call(ExtractBioController::class.'@extract_bio_part_two',
            [
                'searched_user'     => $searched_user,
                'date'              => $date,
                'official'          => $official,
                'merged'    => $merged

            ]);            

                //---to prepare 'object' number hours tardiness
            $tardi = app()->call(ExtractBioController::class.'@extract_tardi',
            [                
                'official'          =>  $official,
                'day'               =>  $day,
                'bio_punch'         =>  $punch,
            ]); 

            $am_late    = $tardi->am_late;
            $pm_late    = $tardi->pm_late;            
            $late       = ($am_late??false) + ($pm_late??false)- $ten_min_allowance;

            $am_und     = $tardi->am_und;
            $pm_und     = $tardi->pm_und;
            $under      = ($am_und??false) + ($pm_und ?? false);
            
            $tardiness = null;
            $whole_day = null;
            $required_h = null ;
            $required_h_late = null;
            $required_h_und = null;
            $type = null;
            
            if ($official->am_in && !$punch->am_in || $official->am_out && !$punch->am_out ||

                $official->am_in && $punch->am_in >= $official->am_out ||

                $official->am_in && $punch->am_out <= $official->am_in ||

                $official->pm_in && !$punch->pm_in || $official->pm_out && !$punch->pm_out||                

                $official->pm_in && $punch->pm_in >= $official->pm_out ||

                $official->pm_in && $punch->pm_out <= $official->pm_in ||

                // $punch->all_bio_punches[0]->date_bio == '040323' ||
                $am_late >= $official->am_num_hr && $official->am_num_hr != 0||
                $am_und  >= $official->am_num_hr && $official->am_num_hr != 0||
                $pm_late >= $official->pm_num_hr && $official->pm_num_hr != 0||
                $pm_und  >= $official->pm_num_hr && $official->pm_num_hr != 0 
               )  
            {
                $type = 'ABS';
                               
                //Abs n_hour if wholeday
                if(!$punch->am_in && !$punch->pm_in || ($punch->am_render + $punch->pm_render) < 1) {

                    $required_h = $official->am_num_hr + $official->pm_num_hr ;

                //Abs n_hour in AM only
                } elseif(!$punch->am_in || !$punch->am_out || $am_late >=  $official->am_num_hr ||
                        $am_und >=  $official->am_num_hr || $official->am_in && $punch->am_in >= $official->am_out
                    ){

                    $required_h = $official->am_num_hr;

                //ABs n_hour in PM only
                } elseif(!$punch->pm_in || !$punch->pm_out ||  $pm_late >=  $official->pm_num_hr ||
                        $pm_und >=  $official->pm_num_hr || $official->pm_in && $punch->pm_in >= $official->pm_out
                    ){

                    $required_h = $official->pm_num_hr;
                } 

                //this is if there are absences and tardiness in one day
                if($late > 0 && $under > 0 || 
                    //if in 2 enries only ; late am in with am out falls in pm
                    // ($am_late < $official->am_num_hr || $pm_late < $official->pm_num_hr) 
                    $late && $punch->am_in > $official->am_in && $punch->am_in < $official->am_out && $am_late - $ten_min_allowance > 0|| 
                    $late && $punch->pm_in > $official->pm_in && $punch->pm_in < $official->pm_out && $pm_late - $ten_min_allowance > 0||
                    $under && $punch->am_out < $official->am_out && $punch->am_out > $official->am_in ||
                    $under && $punch->pm_out < $official->pm_out && $punch->pm_out > $official->pm_in)
                {
                        $tardiness = 'abs_lte_und';
                        $type_late = 'LTE';
                        $type_under = 'UND';
                        $required_h_late = $late ;
                        $required_h_und = $under;

                        $am_late? $late_count++ : '';
                        $pm_late? $late_count++ : '';
                }
            }
            // __________________________________________________________

            //lates and undertime OUTSIDE absences
            elseif($late > 0 && $under > 0)
            {
                $tardiness = 'lte_und';
                $type = 'LTE';
                $required_h = $late;

                $am_late? $late_count++ : '';
                $pm_late? $late_count++ : '';
                
            }
            elseif ($late > 0)
            {
                $type = 'LTE';
                $required_h = $late;

                $am_late? $late_count++ : '';
                $pm_late? $late_count++ : '';
            }    
            elseif ($under > 0)
            {
                $type = 'UND';
                $required_h = $under;
                
            }
            else {
                $type = 'no_tardi';
                $required_h = $official->am_num_hr ;
            }
            // ________________________________________________________

            if(!isset($searched_user->shift->$am_in) ||
                $required_h == 0 || 
                !isset($searched_user->shift->$pm_in))
                {}  
            
            elseif ($type == 'no_tardi')
                {}

            else {
               
                return (object) [
                    'user' => $searched_user,
                    // 'student_id'=> $searched_user->student_id,
                    // 'name'=> $searched_user->name,
                    // 'timecard'=> $searched_user->timecard,
                    'date'=> $d_date,                    
                    'month'=> $date, 
                    'type'=> $type,
                    'required_h'=> $required_h,

                    'type_late' => $tardiness == 'abs_lte_und' ? $type_late : false,
                    'required_h_late' => $tardiness == 'abs_lte_und' ? $required_h_late : false,
                    'type_under' => $tardiness == 'abs_lte_und' ? $type_under : false,                    
                    'required_h_und' => $tardiness == 'abs_lte_und' ? $required_h_und : false,

                    'ws_double'=> $tardiness == 'lte_und' ? $under : false,
                    'bio_daily_array' => $date->format('mdy'),
                    'all_bio_punches' =>  $punch->all_bio_punches,
                    'punch' => $punch,
                    'official' => $official,
                    'late' => $late,
                    'late_count' => $late_count

                ];
            }            

            // ________________________________________________________

                // return (object) [
                //     'user' => $searched_user,
                //     // 'student_id'=> $searched_user->student_id,
                //     // 'name'=> $searched_user->name,
                //     // 'timecard'=> $searched_user->timecard,
                //     'date'=> $d_date,                    
                //     'type'=> $type,
                //     'required_h'=> $required_h,

                //     'type_late' => $tardiness == 'abs_lte_und' ? $type_late : false,
                //     'required_h_late' => $tardiness == 'abs_lte_und' ? $required_h_late : false,
                //     'type_under' => $tardiness == 'abs_lte_und' ? $type_under : false,                    
                //     'required_h_und' => $tardiness == 'abs_lte_und' ? $required_h_und : false,

                //     'ws_double'=> $tardiness == 'lte_und' ? $under : false,
                //     'bio_daily_array' => $date->format('mdy'),
                //     'all_bio_punches' =>  $punch->all_bio_punches,
                //     'punch' => $punch,
                //     'official' => $official,
                //     'late' => $late
            // ];

            
        })->toArray();        
        
        return $mappedArray;
    }
    
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

            if( $searched_user->manual_shifts->pluck('date')->contains( $date->format('Y-m-d')))
            {                             
                $Schedule_id =  $searched_user->manual_shifts->where('date',$date->format('Y-m-d'))
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

            $official_in = $searched_user->schedule->$sched_in??false;

            $official_out = $searched_user->schedule->$sched_out??false;    

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

                 

            if( $searched_user->manual_shifts->pluck('date')->contains( $date->format('Y-m-d')))
            {                             
                $Schedule_id =  $searched_user->manual_shifts->where('date',$date->format('Y-m-d'))
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
