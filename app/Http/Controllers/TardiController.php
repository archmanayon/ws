<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Term;
use App\Models\Tardi;
use App\Models\Punch;
use App\Models\Schedule;
use App\Models\Rawbio;
use App\Models\Setup;
use App\Models\tardi_description;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Carbon\CarbonPeriod;


class TardiController extends Controller
{
    public function show()
    {
        $tardis = Tardi::find(request('conforme'))??false;

        if($tardis){

            return view('tardi_variance',
            [

                'tardis' => $tardis

            ]);

        } else {

            return redirect()->route('show_tardi')
            // ->with([
                // 'user_session' => $user

            // ])
            ;
        }

    }

    public function show_tardi()
    {

        return view('tardi',
        [
            'user'          => auth()->user()??false
        ]);

    }

    public function post_tardi()
    {

        $tardis = Tardi::find(request('conforme'))??false;

        if($tardis){

            return view('tardi_variance',
            [
                'tardis'    => $tardis

                // 'tasks'         => session('task_session')??false,
                // 'currentDate'   => session('currentDate')??false,
                // 'current_time'  => session('current_time')??false,
                // 'current_task' => session('current_task')??[]

             ]);
        }
    }

    public function conforme()
    {
        $Date           = Carbon::now('Asia/Kuala_Lumpur');
        // $currentDate    = $Date->format('m/d/y');
        // $current_time   = $Date->format('Hi');

        Tardi::find(request('tardis_id'))?->update([

            'conforme'    =>  'sent',
            'con_date'    =>  $Date
        ]);

        return redirect()->route('show_tardi')

        ;

    }

    // 01
    public function tardi_group()
    {
        return view('tardi_group',
            [
                'user'          => auth()->user() ?? false,
                'group'         => auth()->user()->heads
                // 'tasks'         => session('task_session')??false,
                // 'currentDate'   => session('currentDate')??false,
                // 'current_time'  => session('current_time')??false,
                // 'current_task' => session('current_task')??[]

            ]
        );
    }

    // 02

    public function staff_variance()
    {
        $tardis = Tardi::find(request('pre_address'))??false;
        
        $holiday = array(
             "08-21-23", "08-28-23", "09-09-23", "10-30-23", "10-31-23", "11-01-23", "11-02-23"
        );
        $start_date = Carbon::create(Setup::find(3)->date)->format('Y-m-d')??false;
        $end_date = Carbon::create(Setup::find(2)->date)->format('Y-m-d')??false;

        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection = collect($dates);
        $count_dates = $period->count();

        $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
        [
            'collection_of_dates' => $collection,
            'searched_user'=> $tardis->user??false,
            'holiday' =>$holiday
        ]);

       
        if($tardis){

            return view('tardi_staff',
            [

                'mappedUser'    => $user,
                'term' => Term::all()->where('active',1)->first(),
                'tardi_desc' => tardi_description::all()??false,
                // 'users'     => $test_string,
                'users'         => auth()->user(),
                'payroll_start' => $start_date??0,
                'payroll_end'   => $end_date??0,

                'tardis' => $tardis
                 // 'tasks'         => session('task_session')??false,
                 // 'currentDate'   => session('currentDate')??false,
                 // 'current_time'  => session('current_time')??false,
                 // 'current_task' => session('current_task')??[]

            ]);

        } else {

            return redirect()->route('show_tardi_group')
            // ->with([
                // 'user_session' => $user

            // ])
            ;
        }

    }

    public function tardi_staff()
    {

        $tardis = Tardi::find(request('pre_address')) ?? false;

        if ($tardis) {

            return view('tardi_staff',
                [
                    'tardis' => $tardis
                    // 'tasks'         => session('task_session')??false,
                    // 'currentDate'   => session('currentDate')??false,
                    // 'current_time'  => session('current_time')??false,
                    // 'current_task' => session('current_task')??[]

                ]
            );
        } else {

            return redirect()->route('show_tardi_group')
                // ->with([
                // 'user_session' => $user

                // ])
            ;
        }
    }

    public function post_address()
    {
        $user           = auth()->user()??false;
        $Date           = Carbon::now('Asia/Kuala_Lumpur');

        $sige = Tardi::find(request('post_address'))->update([

            'head_sig'  =>  request('head_email'),
            'sig_date'  =>  $Date,
            'remarks'   =>  request('h_remarks'),

        ]);

        return redirect()->route('show_tardi_group')

        ;


    }

    public function show_all()
    {
        $holiday = array(
             "08-21-23", "08-28-23", "09-09-23", "10-30-23", "10-31-23", "11-01-23", "11-02-23"
        );

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

            $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
            [
                'collection_of_dates' => $collection_of_dates,
                'searched_user'=> $searched_user,
                'holiday' =>$holiday
            ]);

            return $user;

        });

        return view ('tardi_process',[
            'term' => Term::all()->where('active',1)->first(),
            'tardi_desc' => tardi_description::all()??false,
            'mappedUser' =>  $mappedArray

        ]);
    }

    public function process(Request $request)
    {
        // $user_all = User::with(['shift', 'manual_shifts', 'update_bios'])->get();

        $holiday = array(
             "08-21-23", "08-28-23", "09-09-23", "10-30-23", "10-31-23", "11-01-23", "11-02-23"
        );

 
        if( request('save_tardi') ){ 

            $data = $request->input('lte');            

            // $validatedData = $request->validate([
            //     'lte.*.usertardidesc'    => 'unique:tardis,usertardidesc'
                
            // ]);  

            // Extract the values from the input array and prepare them for bulk insertion

            $insertData = [];

            foreach ($data as $key => $value) {
                // // Additional data to be validated (not from the input form)
                    $additionalData = [
                        'user_id'               => $value['user_id'],
                        'term_id'               => $value['term_id'],
                        'month'                 => $value['month'],
                        'total'                 => $value['total'],
                        'tardi_description_id'  => $value['tardi_description_id'],
                        'head_id'               => $value['head_id'],
                        'usertardidesc'         => $value['usertardidesc'],                                    
                    ]; 

                    // // Define validation rules for additional data
                    $additionalValidationRules = [

                        'usertardidesc'         =>'required|unique:tardis,usertardidesc'
                        
                    ];

                    // // Define custom error messages for additional data
                    $customErrorMessages = [

                        'usertardidesc.unique'     => 'Tardiness Already Saved'
                        
                    ];                

                // // Create a new Validator instance and validate the additional data
                $validator = Validator::make($additionalData, $additionalValidationRules,$customErrorMessages);               

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator);
                }               

                $insertData[] = [
                    'user_id'               => $additionalData['user_id'],
                    'term_id'               => $additionalData['term_id'],
                    'month'                 => $additionalData['month'],
                    'total'                 => $additionalData['total'],
                    'tardi_description_id'  => $additionalData['tardi_description_id'],
                    'head_id'               => $additionalData['head_id'],
                    'usertardidesc'         => $additionalData['usertardidesc'],
                    // Add more columns as needed
                ];
            }           
            
            Tardi::insert($insertData);

            return redirect()->back()->withErrors($validator);
        }

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

            $user = app()->call(AbsenceCalendarController::class.'@adea_bio',
            [
                'collection_of_dates' => $collection_of_dates,
                'searched_user'=> $searched_user,
                'holiday' =>$holiday
            ]);

                return $user;

            }
        );

        return view ('tardi_process',[

            'mappedUser' =>  $mappedArray,
            'term' => Term::all()->where('active',1)->first(),
            'tardi_desc' => tardi_description::all(),   
        ]);
    }


}
