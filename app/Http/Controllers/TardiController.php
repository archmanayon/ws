<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Term;
use App\Models\Punch;
use App\Models\Schedule;
use App\Models\Rawbio;
use App\Models\Tardi_description;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Tardi;

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

        if($tardis){

            return view('tardi_staff',
            [

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
            "08-21-23", "08-28-23", "09-09-23"
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
            'tardi_desc' => Tardi_description::all(),
            'mappedUser' =>  $mappedArray

        ]);
    }

    public function process(Request $request)
    {
        // $user_all = User::with(['shift', 'manual_shifts', 'update_bios'])->get();

        $holiday = array(
            "08-21-23", "08-28-23", "09-09-23"
        );

 
        if( request('save_tardi') ){

            $data = $request->input('data');

            $validatedData = $request->validate([
                'data.*.user_id'    => 'required',
                'data.*.term_id'    => 'required',
                'data.*.month'      => 'required',
                'data.*.total'      => 'required',
                'data.*.tardi_description_id' => 'required',
                'data.*.head_id'    => 'required'
                
            ]);    

            Tardi::insert($validatedData);

            $start_date = request('start_date')?? 0;
            $end_date = request('end_date')?? 0;
            $period = CarbonPeriod::create($start_date, $end_date);
            $dates = $period->toArray();
            $collection_of_dates = collect($dates);
            $count_dates = $period->count();
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
            'tardi_desc' => Tardi_description::all(),   
        ]);
    }


}
