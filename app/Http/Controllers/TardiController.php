<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TardiController extends Controller
{
    public function show()
    {
       
        return view('tardi_variance',
        [
            'user'          => auth()->user()??false
            // 'tasks'         => session('task_session')??false,
            // 'currentDate'   => session('currentDate')??false,
            // 'current_time'  => session('current_time')??false,
            // 'current_task' => session('current_task')??[]
            
        ]);
        
    }

    public function show_tardi()
    {
       
        return view('tardi',
        [
            'user'          => auth()->user()??false
            // 'tasks'         => session('task_session')??false,
            // 'currentDate'   => session('currentDate')??false,
            // 'current_time'  => session('current_time')??false,
            // 'current_task' => session('current_task')??[]
            
        ]);
        
    }

    public function show_staff()
    {
       
        return view('tardi_staff',
        [
            'user'          => auth()->user()??false
            // 'tasks'         => session('task_session')??false,
            // 'currentDate'   => session('currentDate')??false,
            // 'current_time'  => session('current_time')??false,
            // 'current_task' => session('current_task')??[]
            
        ]);
        
    }
}
