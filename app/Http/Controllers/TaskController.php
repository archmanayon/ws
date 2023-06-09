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
       
        return view('task',
        [
            'user'          => session('user_session')??(auth()->user()??false)
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
            'biotext'   =>  $user->timecard.$currentDate.$current_time
        ]);      
        
        return redirect()->route('show_task')->with([

            'user_session' => $user
            // 'task_session' => $task,
            // 'currentDate'  => $currentDate,
            // 'current_time' => $current_time,
            // 'current_task' => $user->tasks
            // 'current_task' => $user->tasks
        ]);
        
    }
}
