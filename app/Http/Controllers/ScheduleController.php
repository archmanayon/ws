<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            'searched_user'=> User::find(auth()->id()), 
            'holiday' =>$holiday
        ]);

    return view ('report',[

        'mappedArray' =>  $AbsenceCalendarController,
        'searched_user'=> User::find(auth()->id()), 

    ]);
}

   public function print_all_abs() 
    {

        $holiday = ["01-01-23", "01-02-23","01-03-23",
                            "01-04-23","01-05-23","01-06-23",
                            "01-07-23","01-16-23",
                            "02-24-23", "02-25-23"];
        $manual_shift;         

        $month = 2;

        $year = 2023;

        $start_date = '2023-02-01';
        $end_date = '2023-02-28';

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

        return view ('all_absences',
        [

            'users' => $users,

            'mappedArray' =>  $mappedArray

        ]);
    }
}
