<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Punch;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\AuthenticatedSessionController;

class TaskController extends Controller
{
    public function show()
    {
        $user           = session('user_session');
        $tasks           = session('task_session');
       
        return view('task',[
            'user'          => $user,
            'tasks'          => $tasks,
            'currentDate'   => session('currentDate'),
            'current_time'  => session('current_time')
            
        ]);
        
    }

    public function store()
    {
        $user           = auth()->user()??false;
        $task           = request('task_text');

        $Date           = Carbon::now('Asia/Kuala_Lumpur');                
        $currentDate    = $Date->format('mdy');
        $current_time   = $Date->format('Hi');

        $tasks          = $user->tasks;
        // $in_out         = $tasks->pluck('biotext')->last() === 'I' ? 'O' : 'I';      

        $tasks = Task::create([
            'user_id'   =>  $user->id,
            'task_done' =>  $task,
            'biotext'   =>  $user->timecard.$currentDate.$current_time
        ]);      
        
        return redirect()->route('show_task')->with([
            'user_session' => $tasks->user_id[0],
            'task_session' => $tasks->task_done,
            'currentDate'  => $currentDate,
            'current_time' => $current_time
        ]);
        
    }
}
