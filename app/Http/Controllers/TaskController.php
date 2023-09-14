<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Punch;
use App\Models\Task;
use App\Models\Update_bio;
use App\Models\Shift;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\AuthenticatedSessionController;


class TaskController extends Controller
{
    public function show()
    {

        return view('task',
        [
            'user'          => auth()->user()??false
            // 'tasks'         => session('task_session')??false,
            // 'currentDate'   => session('currentDate')??false,
            // 'current_time'  => session('current_time')??false,
            // 'current_task' => session('current_task')??[]

        ]);

    }

    public function store()
    {
        $user           = auth()->user()??false;
        $task           = request('task_text');

        $Date           = Carbon::now('Asia/Kuala_Lumpur');
        $currentDate    = $Date->format('mdy');
        $current_time   = $Date->format('Hi');

        $ampm  = '_am_';
        $io = 'in';
        $day_inout = $Date->format('l').$ampm.$io;
        
        $official_time = $user->shift->$day_inout;        

        $biotext   =  $user->timecard.$currentDate.$official_time.'I';        

        // Additional data to be validated (not from the input form)
            $additionalData = [
                'biotext' => $biotext
            ];

            // Define validation rules for additional data
            $additionalValidationRules = [
                'biotext' => 'unique:tasks,biotext'
                
            ];

            // Define custom error messages for additional data
            $customErrorMessages = [
                
                'biotext.unique'     => 'You have already submitted task for the Day'
                
            ];

            // Create a new Validator instance and validate the additional data
            $validator = Validator::make($additionalData, $additionalValidationRules,$customErrorMessages);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator->errors());
            }
        // ____________________

            
            // dd($additionalData['biotext']);

        $tasks = Task::create([
            'user_id'   =>  $user->id,
            'task_done' =>  $task,
            'status'    =>  null,
            // 'remarks'   =>  ,
            'head_id'      =>  $user->head->id,
            'biotext'   => $additionalData['biotext']
        ]);

        return redirect()->route('show_task')
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

    public function endorse()
    {
        $user           = auth()->user()??false;
        $head_remarks   = request('head_remarks');
        $stat_option    = request('stat_option');
        $tasks          = Task::find(request('task_id'));
        $Date           = Carbon::create($tasks->created_at)->setTimeZone('Asia/Kuala_Lumpur');

        $tasks->update([

            'status'    =>  $stat_option,
            'remarks'   =>  $head_remarks
        ]);

        $print_array = [];

        if($stat_option == 1){

            // dd($tasks->user->shift->Friday_am_in);

            // $bio_text_code;

            for ($i = 0; $i < 4; $i++) {
                // Your code to be executed 4 times goes here
                // You can use the $i variable to keep track of the current iteration

                $inout = $i % 2 == 0 ?'I':'O';

                if($i <= 1 ){

                    // $ampm  = "_am_".$i % 2 == 0 ? "in":"out";
                    $ampm  = '_am_';
                    $io = $i % 2 == 0 ?'in':'out';
                    $day_inout = $Date->format('l').$ampm.$io;
                    $official_time = $tasks->user->shift->$day_inout;
                    $print_array[] = $tasks->user->timecard. $Date->format('mdy').$official_time.$inout;
                    $bio_text_code = $tasks->user->timecard. $Date->format('mdy').$official_time.$inout;

                } else {

                    // $ampm  = "_pm_" . $i % 2 == 0 ? "in" : "out";                
                    $ampm  = "_pm_";
                    $io = $i % 2 == 0?'in':'out';
                    $day_inout = $Date->format('l').$ampm.$io;
                    $official_time = $tasks->user->shift->$day_inout;
                    $print_array[] = $tasks->user->timecard. $Date->format('mdy').$official_time.$inout;
                    $bio_text_code = $tasks->user->timecard. $Date->format('mdy').$official_time.$inout;
                }       

                // Update_bio::create([
                //     'name'      => $tasks->user->name,
                //     'time_card' => $tasks->user->timecard,
                //     'date'      => $Date->format('mdy'),
                //     'hour'      => $official_time,
                //     'in_out'    => $inout,
                //     'biotext'   => $bio_text_code,
                //     'punchtype_id'   => 9,
                //     'reason'    => 'Work From Home Task'
                // ]);
                
            }            
            
        }
        // dd($print_array);

        return redirect()->route('show_dept_head')
            // ->with([
                // 'user_session' => $user
                // 'task_session' => $task,
                // 'currentDate'  => $currentDate,
                // 'current_time' => $current_time,
                // 'current_task' => $user->tasks
                // 'current_task' => $user->tasks
            // ])
        ;

    }
}
