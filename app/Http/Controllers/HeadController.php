<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HeadController extends Controller
{
    public function show()
    {
       
        return view('dept_head',
        [
            'user'          => session('user_session')??(auth()->user()??false)
            // 'tasks'         => session('task_session')??false,
            // 'currentDate'   => session('currentDate')??false,
            // 'current_time'  => session('current_time')??false,
            // 'current_task' => session('current_task')??[]
            
        ]);
        
    }
}
