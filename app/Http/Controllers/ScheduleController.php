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

        $AbsenceCalendarController = app()->call(AbsenceCalendarController::class.'@calendar_absences',
            [
                'collection_of_dates' => $collection,
                'searched_user'=> User::find(5), 
                'holiday' =>$holiday
            ]);

        return view ('print',[

            'mappedArray' =>  $AbsenceCalendarController

        ]);
    }

    public function owner_abs(User $ws) {

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

   public function print_all_abs() 
    {
        $user_all = User::all();

        $holiday = ["01-01-23", "01-02-23","01-03-23",
                            "01-04-23","01-05-23","01-06-23",
                            "01-07-23","01-16-23",
                            "02-24-23", "02-25-23"];
        $manual_shift;                

        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;

        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();
        $users = $user_all->pluck('id');           
        
        $mappedArray = collect($users)
            ->map(function ($user) use ($collection, $holiday){

                $user = app()->call(AbsenceCalendarController::class.'@calendar_absences',
                [
                    'collection_of_dates' => $collection,
                    'searched_user'=> User::find($user), 
                    'holiday' =>$holiday
                ]);

                return $user;
                
            }
        ); 

        // to get whole date from column
        $test_string = Biometric::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $user_all[7]->timecard);
        
        // to get specifit 'string' from date from column
        $subString = $test_string->selectRaw
            ('
                SUBSTRING(biotext, 1, 6) AS timecard,
                SUBSTRING(biotext, 7, 6) AS date,
                SUBSTRING(biotext, 13, 4) AS hour,
                SUBSTRING(biotext, 17, 1) AS in_out
            ')->get();

        return view ('all_absences',
        [
            // 'users' => $test_string,
            'bio' => $subString,
                        
            'mappedArray' =>  $mappedArray ?? false

        ]);
    }

    public function bio_abs() 
    {   

        $holiday = ["01-01-23", "01-02-23","01-03-23",
                            "01-04-23","01-05-23","01-06-23",
                            "01-07-23","01-16-23",
                            "02-24-23", "02-25-23"];
        $manual_shift;                

        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;

        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();
        $users = User::all()->pluck('id');           
        
        $mappedArray = collect($users)

            

            ->map(function ($user) use ($collection, $holiday){



                $user = app()->call(AbsenceCalendarController::class.'@calendar_absences',
                [
                    'collection_of_dates' => $collection,
                    'searched_user'=> User::find($user), 
                    'holiday' =>$holiday
                ]);

                return $user;
                
            }
        ); 
        // to get whole date from column
        $test_string = User::where(DB::raw('SUBSTRING(username, 2, 3)'), '=', '210')->get()->pluck('username');
        // to get specifit 'string' from date from column
        $subString = User::selectRaw('SUBSTRING(username, 2, 4) AS stripped_username')->get();

        return view ('all_absences',
        [
            // 'users' => $test_string,
            'users' => $subString,
                       
            'mappedArray' =>  $mappedArray ?? false

        ]);
    }

}
