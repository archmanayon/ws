<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Biometric;
use App\Models\ManualShift;
use App\Models\Punch;
use App\Models\Schedule;
use App\Models\Rawbio;
use App\Models\Term;
use App\Models\Tardi;
use App\Models\tardi_description;

use App\Http\Controllers\AbsenceCalendarController;
use App\Http\Controllers\BiometricController;

use App\Models\Shift;
use App\Models\Update_bio;
use App\Models\Setup;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Support\Str;


class ScheduleController extends Controller{    

    private  $holiday = array( "08-21-23", "08-28-23", "09-09-23", "10-30-23", "10-31-23", "11-01-23", "11-02-23", "11-27-23");

    // individual search______________________________
    public function absences_all()
    {
        $users  = User::all()->where('active', true)->sortBy('name');        

        $searched_user = $users->where('id', request('find_user'))->first()??false;

        // $searched_user = User::find(request('find_user'))??false;

        // $holiday = array( "08-21-23", "08-28-23", "09-09-23", "10-30-23", "10-31-23", "11-01-23", "11-02-23"
        //                 );

        $holiday = $this->holiday;        

        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();

        $mappedDates = app()->call(AbsenceCalendarController::class.'@adea_bio',
        [
            'collection_of_dates' => $collection,
            'searched_user'=> $searched_user,
            'holiday' =>$holiday
        ]);

        return view ('print',[

            // used for searching user dropdown
            'users'         => $users,
            'mappedUser'    => $mappedDates,
            // 'update_bio'    => Update_bio::find(2),
            // 'updated_bio_2' => Update_bio::where('time_card', $searched_user->timecard)->where('date', '040523')->exists(),
            // 'updated_bio_2' => $searched_user->update_bios->where('date', '042423')->pluck('date')->contains('042423'),
            // 'updated_bio_2' => $searched_user->update_bios->contains('date', '042423'),
            // 'updated_bio_3' => $searched_user->update_bios->where('date', '042823')

            // 'updated_bio_3' => Update_bio::where('time_card', $searched_user->timecard)->where('date', '040523')->get()

        ]);
    }

    public function owner_abs()
    {

        // $payroll_start  = Setup::find(2);
        $payroll_start  = Carbon::create(Setup::find(1)->date)->format('Y-m-d')??false;
        $payroll_end    = Carbon::create(Setup::find(2)->date)->format('Y-m-d')??false;

        $holiday = $this->holiday;

        $start_date = request('start_date')?
        (request('start_date') < $payroll_start ? $payroll_start : request('start_date')):$payroll_start;

        $end_date = request('end_date')?
        (request('end_date') > $payroll_end ? $payroll_end : request('end_date')):$payroll_end;

        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();

        $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
        [
            'collection_of_dates' => $collection,
            'searched_user'=> auth()->user()??false,
            'holiday' =>$holiday
        ]);

        return view ('report',[

            'mappedUser'    => $user,
             // 'users'     => $test_string,
            'users'         => auth()->user(),
            'payroll_start' => $start_date??0,
            'payroll_end'   => $end_date??0

        ]);
    }

    public function wsreport()
    {

        // $payroll_start  = Setup::find(2);
        $payroll_start  = "2023-08-01";
        $payroll_end    = Carbon::create(Setup::find(4)->date)->format('Y-m-d')??false;
        
        $holiday = $this->holiday;

        $start_date = request('start_date')?
        (request('start_date') < $payroll_start ? $payroll_start : request('start_date')):$payroll_start;

        $end_date = request('end_date')?
        (request('end_date') > $payroll_end ? $payroll_end : request('end_date')):$payroll_end;

        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();

        $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
        [
            'collection_of_dates' => $collection,
            'searched_user'=> auth()->user()??false,
            'holiday' =>$holiday
        ]);

        return view ('wsreport',[

            'mappedUser'    => $user,
             // 'users'     => $test_string,
            'users'         => auth()->user(),
            'payroll_start' => $start_date??0,
            'payroll_end'   => $end_date??0

        ]);
    }


    public function tardi_in_dash()
    {

        $holiday = $this->holiday;

        $start_date = Carbon::create(Setup::find(3)->date)->format('Y-m-d')??false;

        $end_date = Carbon::create(Setup::find(2)->date)->format('Y-m-d')??false;

        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();

        $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
        [
            'collection_of_dates' => $collection,
            'searched_user'=> auth()->user()??false,
            'holiday' =>$holiday
        ]);

        return view ('dashboard',[

            'mappedUser'    => $user,
            'term' => Term::all()->where('active',1)->first(),
            'tardi_desc' => tardi_description::all()??false,
             // 'users'     => $test_string,
            'users'         => auth()->user(),
            'payroll_start' => $start_date??0,
            'payroll_end'   => $end_date??0

        ]);
    }

    public function owner_abs_store(User $ws)
    {

        // $payroll_start  = Setup::find(2);
        $payroll_start  = Carbon::create(Setup::find(1)->date)??false;
        $payroll_end    = Carbon::create(Setup::find(2)->date) ?? false;

        $holiday = $this->holiday;

        // $start_date = request('start_date')?? 0;
        $start_date = request('start_date') < $payroll_start->format('Y-m-d') ?
                        $payroll_start->format('Y-m-d') : request('start_date');

        // $start_date = request('start_date') ?? request('start_date') < $payroll_start->format('Y-m-d') ?
        //                 $payroll_start->format('Y-m-d') : (request('start_date')??false);

        $end_date = request('end_date') > $payroll_end->format('Y-m-d') ?
                        $payroll_end->format('Y-m-d') : request('end_date');

        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();


        $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
        [
            'collection_of_dates' => $collection,
            'searched_user'=> $ws??false,
            'holiday' =>$holiday
        ]);

        return view ('report',[

            'mappedUser'    => $user,
             // 'users'     => $test_string,
            'users'         => $ws,
            'payroll_start' => $payroll_start ?? false,
            'payroll_end'   => $payroll_end ?? false

        ]);
    }

    //  all WS only_____________________________________
    public function print_all_abs_old()
    {
        // $user_all = User::with(['shift', 'manual_shifts', 'update_bios'])->get();

        $holiday = $this->holiday;

        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection_of_dates = collect($dates);
        $count_dates = $period->count();

        $mappedArray = collect(User::all()->where('active', true)->where('role_id', 1)->sortBy('name'))
        // User::with(['manual_shifts','shift', 'update_bios'])->get()
            ->map(function ($user) use ($collection_of_dates, $holiday){

                $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
                [
                    'collection_of_dates' => $collection_of_dates,
                    'searched_user'=> $user,
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

        $holiday = $this->holiday;

        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection_of_dates = collect($dates);
        $count_dates = $period->count();

        $users = User::where(function ($query) {
            $query->where('role_id', '=', 2)->orWhere('role_id', '=', 5);
        })->get();

        $mappedArray = collect($users->where('active',true)->sortBy('name'))
            ->map(function ($searched_user) use ($collection_of_dates, $holiday){     

            $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
            [
                'collection_of_dates' => $collection_of_dates,
                'searched_user'=> $searched_user,
                'holiday' =>$holiday
            ]);

            return $user;

        });

        return view ('adea',[

            'mappedUser' =>  $mappedArray

        ]);
    }

    public function text_files()
    {
        // $payroll_start  = Setup::find(2);
        $payroll_start  = Carbon::create(Setup::find(1)->date) ?? 0;
        $payroll_end    = Carbon::create(Setup::find(2)->date) ?? 0;

        $holiday = $this->holiday;

        // $start_date = request('start_date')?? request('start_date') < $payroll_start->format('Y-m-d') ?
        //         $payroll_start->format('Y-m-d') : request('start_date') ?? 0;

        // $end_date = request('end_date') ?? request('end_date') > $payroll_end->format('Y-m-d') ?
        //         $payroll_end->format('Y-m-d') : request('end_date') ?? 0;

        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection_of_dates = collect($dates);
        $count_dates = $period->count();

        $users = User::where(function ($query) {
            $query->where('role_id', '=', 2)
                  ->orWhere('role_id', '=', 5);
        })->get();


        $mappedArray = collect($users->where('active',true)->sortBy('name'))
            ->map(function ($searched_user) use ($collection_of_dates, $holiday){     

            $user = app()->call(BiometricController::class.'@text_files_part_2',
            [
                'collection_of_dates' => $collection_of_dates,
                'searched_user'=> $searched_user,
                'holiday' =>$holiday
            ]);

            return $user;

        }); 

        return view('text_files', [

            'mappedUser' =>  $mappedArray,
            'payroll_start' => $payroll_start ?? false,
            'payroll_end'   => $payroll_end ?? false

        ]);
    }

    // public function text_files()
        // {
        //     $holiday = array("01-05-23","01-06-23",
        //                         "02-24-23", "02-25-23",
        //                         "04-06-23", "04-07-23",
        //                         "04-08-23", "04-10-23", "05-01-23",
        //                         "04-21-23", "06-12-23", "06-28-23"
        //                     );

        //     $start_date = request('start_date') ?? 0;
        //     $end_date = request('end_date') ?? 0;
        //     $period = CarbonPeriod::create($start_date, $end_date);
        //     $dates = $period->toArray();
        //     $collection_of_dates = collect($dates);
        //     $count_dates = $period->count();

        //     $mappedArray = collect(User::all()->where('active',true)->where('role_id', 2))
        //     ->map(
        //         function ($user) use ($collection_of_dates, $holiday) {

        //             $user = app()->call(
        //                 BiometricController::class . '@text_files',
        //                 [
        //                     'collection_of_dates' => $collection_of_dates,
        //                     'searched_user' => User::find($user->id),
        //                     'holiday' => $holiday
        //                 ]
        //             );

        //             return $user;
        //         }
        //     );

        //     return view('text_files', [

        //         'mappedUser' =>  $mappedArray

        //     ]);
    // }

    // public function owner_abs(User $ws){

        //     $holiday = array("01-01-23", "01-02-23","01-03-23",
        //                         "01-04-23","01-05-23","01-06-23",
        //                         "01-07-23","01-16-23", "05-01-23",
        //                         "02-24-23", "02-25-23");

        //     // $num_days = in_array($month_, $thirty_days) ? '30':
        //         // (in_array($month_, $thirty_one)? '31':
        //         //     ($month_ =='0'? '0':'28')
        //     // );

        //     $start_date = request('start_date')?? 0;
        //     $end_date = request('end_date')?? 0;

        //     $period = CarbonPeriod::create($start_date, $end_date);
        //     $dates = $period->toArray();
        //     $collection = collect($dates);
        //     $count_dates = $period->count();

        //     $AbsenceCalendarController = app()->call(AbsenceCalendarController::class.'@biometrics',
        //         [
        //             'collection_of_dates' => $collection,
        //             'searched_user'=> $ws,
        //             'holiday' =>$holiday
        //         ]);

        //     // to get whole date from column
        //     $test_string = Biometric::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $ws->timecard)
        //     ->where(DB::raw('SUBSTRING(biotext, 7, 6)'), '=',  '021023');

        //     // to get specifit 'string' from date from column
        //     $subString = $test_string->selectRaw
        //         ('
        //             SUBSTRING(biotext, 1, 6) AS timecard,
        //             SUBSTRING(biotext, 7, 6) AS date,
        //             SUBSTRING(biotext, 13, 4) AS hour,
        //             SUBSTRING(biotext, 17, 1) AS in_out
        //         ')->get();

        //     return view ('report',[

        //         'mappedArray' =>  $AbsenceCalendarController,
        //          // 'users' => $test_string,
        //         'bio' => $subString

        //     ]);
    // }



}
