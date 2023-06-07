<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Punch;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\AuthenticatedSessionController;

class TaskController extends Controller
{
    public function show()
    {
        $user = session('user_session');
        $task = session('task_session');
       
        return view('task',[
            'user' => $user,
            'task' => $task
            
        ]);
        
    }

    public function store()
    {
        $user = auth()->user()??false;
        $task = request('task_text');
        
        return redirect('task')->with([
            'user_session' => $user,
            'task_session' => $task
        ]);
        
    }
}
