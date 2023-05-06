<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Biometric;
use App\Models\ManualShift;
use App\Models\Punch;
use App\Models\Schedule;
use App\Http\Controllers\AbsenceCalendarController;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ScheduleController extends Controller{

    public function absences_all()
    {
       
        $find_user = request('find_user')?? 0;

        $holiday = array("01-05-23","01-06-23",
                            "02-24-23", "02-25-23",
                            "04-06-23", "04-07-23",
                            "04-08-23", "04-10-23",
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
            'searched_user'=> User::find($find_user), 
            'holiday' =>$holiday
        ]);
                 

        return view ('print',[

            'users'     => User::all()->sortBy('name'),
            'mappedUser' =>  $user

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

    public function adea_bio_abs() 
    {   $user_all = User::all();
        $holiday = array("04-06-23", "04-07-23","04-08-23",
                            "04-10-23","01-05-23","01-06-23",
                            "01-07-23","01-16-23",
                            "02-24-23", "02-25-23");
        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();

        $bio_punches = app()->call(AbsenceCalendarController::class.'@adea_bio',
            [
                'collection_of_dates' => $collection,
                'searched_user'=> $user_all->find(9), 
                'holiday' =>$holiday
            ]);                      

        return view ('adea',[

            'bio_punches' =>  $bio_punches
        ]);
    }

    public function print_all_abs_old() 
    {
        $user_all = User::all();
        $holiday = array("01-05-23","01-06-23",
                            "02-24-23", "02-25-23",
                            "04-06-23", "04-07-23",
                            "04-08-23", "04-10-23",
                            "04-21-23") ;

        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();
        
        $users = $user_all->pluck('id');           
        
        $mappedArray = collect($users)
            ->map(function ($user) use ($collection, $holiday){

                $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
                [
                    'collection_of_dates' => $collection,
                    'searched_user'=> User::find($user), 
                    'holiday' =>$holiday
                ]);

                return $user;
                
            }
        );       

        return view ('all_absences',[

            'mappedUser' =>  $mappedArray

        ]);
    }
}
