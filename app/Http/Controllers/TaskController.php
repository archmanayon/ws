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

        // $in_out         = $tasks->pluck('biotext')->last() === 'I' ? 'O' : 'I';

        $tasks = Task::create([
            'user_id'   =>  $user->id,
            'task_done' =>  $task,
            'status'    =>  null,
            // 'remarks'   =>  ,
            'head_id'      =>  $user->head->id,
            'biotext'   =>  $user->timecard.$currentDate.$current_time
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


        // $currentDate    = $Date->format('mdy');
        // $current_time   = $Date->format('Hi');
        // $in_out         = $tasks->pluck('biotext')->last() === 'I' ? 'O' : 'I';
        // dd($Date->format('l'));
        // dd($tasks->user->shift->description);

        $tasks->update([

            'status'    =>  $stat_option,
            'remarks'   =>  $head_remarks
        ]);

        for ($i = 0; $i < 4; $i++) {
            // Your code to be executed 4 times goes here
            // You can use the $i variable to keep track of the current iteration
            $in_out = $i % 2 == 0 ? "I" : "O";

            if($i < 2 ){

                $am_pm  = '_am_'.$i % 2 == 0 ? "in" : "out";

            } else {

                $am_pm  = '_pm_' . $i % 2 == 0 ? "in" : "out";

            };

            // Update_bio::create([
            //     'name'      => $tasks->user->name,
            //     'time_card' => $tasks->user->timecard,
            //     'date'      => $Date->format('mdy'),
            //     'hour'      => $Date->format('Hi'),
            //     'in_out'    => $in_out,
            //     'biotext'   => $tasks->user->timecard. $Date->format('mdy') . $Date->format('Hi') . $in_out,
            //     'reason'    => 'Work From Home Task'
            // ]);


        }

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
