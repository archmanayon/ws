<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Head;

class HeadController extends Controller
{
    public function show()
    {
       
        return view('dept_head',
        [
            'user'          => auth()->user()??false
            // 'tasks'         => session('task_session')??false,
            // 'currentDate'   => session('currentDate')??false,
            // 'current_time'  => session('current_time')??false,
            // 'current_task' => session('current_task')??[]
            
        ]);
        
    }

    public function show_all_tasks()
    {
       
        return view('history_tasks',
        [
            'user'          => auth()->user()??false
            // 'tasks'         => session('task_session')??false,
            // 'currentDate'   => session('currentDate')??false,
            // 'current_time'  => session('current_time')??false,
            // 'current_task' => session('current_task')??[]
            
        ]);
        
    }

    public function show_dept_list()
    {
       
        return view('dept_list',
        [
            'department'          => Head::all()->sortBy('department')??false
            // 'tasks'         => session('task_session')??false,
            // 'currentDate'   => session('currentDate')??false,
            // 'current_time'  => session('current_time')??false,
            // 'current_task' => session('current_task')??[]
            
        ]);
        
    }
}
