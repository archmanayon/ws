<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Biometric;
use App\Models\ManualShift;
use App\Models\Punch;
use App\Models\Schedule;
use App\Http\Controllers\AbsenceCalendarController;

use App\Models\Shift;
use App\Models\Update_bio;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ScheduleController extends Controller{

    // individual search______________________________    
    public function absences_all()
    {
        $searched_user = User::find(request('find_user'));        

        $holiday = array("01-05-23","01-06-23",
                            "02-24-23", "02-25-23",
                            "04-06-23", "04-07-23",
                            "04-08-23", "04-10-23", "05-01-23",
                            "04-21-23") ;

        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();
        
        
        $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
        [
            'collection_of_dates' => $collection,
            'searched_user'=> $searched_user??false, 
            'holiday' =>$holiday
        ]);
                 

        return view ('print',[

            'users'         => User::all()->where('active', true)->sortBy('name'),
            'mappedUser'    => $user,
            'update_bio'    => Update_bio::find(2),
            // 'updated_bio_2' => Update_bio::where('time_card', $searched_user->timecard)->where('date', '040523')->exists(),
            // 'updated_bio_2' => $searched_user->update_bios->where('date', '042423')->pluck('date')->contains('042423'),
            // 'updated_bio_2' => $searched_user->update_bios->contains('date', '042423'),
            // 'updated_bio_3' => $searched_user->update_bios->where('date', '042823')
            
            // 'updated_bio_3' => Update_bio::where('time_card', $searched_user->timecard)->where('date', '040523')->get()
            
        ]);
    }

    //  all users_____________________________________
    public function print_all_abs_old() 
    {
        // $user_all = User::with(['shift', 'manual_shifts', 'update_bios'])->get();

        $holiday = array("01-05-23","01-06-23",
                            "02-24-23", "02-25-23",
                            "04-06-23", "04-07-23",
                            "04-08-23", "04-10-23",
                            "04-21-23") ;

        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection_of_dates = collect($dates);
        $count_dates = $period->count();
        
        $mappedArray = collect(User::all()->where('active', true)->where('role_id', 1))
            ->map(function ($user) use ($collection_of_dates, $holiday){

                $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
                [
                    'collection_of_dates' => $collection_of_dates,
                    'searched_user'=> User::find($user->id), 
                    'holiday' =>$holiday
                ]);

                return $user;
                
            }
        );       

        return view ('all_absences',[

            'mappedUser' =>  $mappedArray



        ]);
    }

    ///  all adeans only_____________________________________
    public function adea_bio_abs() 
    {
        // $user_all = User::with(['shift', 'manual_shifts', 'update_bios'])->get();

        $holiday = array("01-05-23","01-06-23",
                            "02-24-23", "02-25-23",
                            "04-06-23", "04-07-23",
                            "04-08-23", "04-10-23", "05-01-23",
                            "04-21-23") ;

        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection_of_dates = collect($dates);
        $count_dates = $period->count();
        
        $mappedArray = collect(User::all()->where('active', true)->where('role_id', 2))
            ->map(function ($user) use ($collection_of_dates, $holiday){

                $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
                [
                    'collection_of_dates' => $collection_of_dates,
                    'searched_user'=> User::find($user->id), 
                    'holiday' =>$holiday
                ]);

                return $user;
                
            }
        );       

        return view ('adea',[

            'mappedUser' =>  $mappedArray



        ]);
    }


    public function owner_abs(User $ws){

        $holiday = array("01-01-23", "01-02-23","01-03-23",
                            "01-04-23","01-05-23","01-06-23",
                            "01-07-23","01-16-23",
                            "02-24-23", "02-25-23");

        // $num_days = in_array($month_, $thirty_days) ? '30':
            // (in_array($month_, $thirty_one)? '31':
            //     ($month_ =='0'? '0':'28')
        // );
        
        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;

        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();

        $AbsenceCalendarController = app()->call(AbsenceCalendarController::class.'@biometrics',
            [
                'collection_of_dates' => $collection,
                'searched_user'=> $ws, 
                'holiday' =>$holiday
            ]);
                
        // to get whole date from column
        $test_string = Biometric::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $ws->timecard)
        ->where(DB::raw('SUBSTRING(biotext, 7, 6)'), '=',  '021023');
        
        // to get specifit 'string' from date from column
        $subString = $test_string->selectRaw
            ('
                SUBSTRING(biotext, 1, 6) AS timecard,
                SUBSTRING(biotext, 7, 6) AS date,
                SUBSTRING(biotext, 13, 4) AS hour,
                SUBSTRING(biotext, 17, 1) AS in_out
            ')->get();

        return view ('report',[

            'mappedArray' =>  $AbsenceCalendarController,
             // 'users' => $test_string,
            'bio' => $subString

        ]);
    }

    
    
}
