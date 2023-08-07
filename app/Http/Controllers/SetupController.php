<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setup;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SetupController extends Controller
{
    public function show()
    {
        $setup = Setup::all()??false;
       
        return view('setup',
        [

            'setup' => $setup

        ]);
    }

    public function store(Request $request)
    {       
        $type_source    = request('type_source');
        $effective_date = request('effective_date');

        $Date           = Carbon::create($effective_date);  
        
        $validate_setup = $request->validate([
            'type_source' => ['required'],
            'effective_date' => ['required', 'date']
        ]);

        $Setup = Setup::create([

            'type' => $validate_setup['type_source'],
            'date' => $validate_setup['effective_date']
        ]);
        
        return redirect()->route('setup_show')
            // ->with([
            //     'user_session' => $user
            //     // 'task_session' => $task,
            //     // 'currentDate'  => $currentDate,
            //     // 'current_time' => $current_time,
            //     // 'current_task' => $user->tasks
            //     // 'current_task' => $user->tasks
            // ])
        ;
        
    }
}
