<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Tardi;

class TardiController extends Controller
{
    public function show()
    {
        $tardis = Tardi::find(request('conforme'))??false; 

        if($tardis){
       
            return view('tardi_variance',
            [
            
                'user'          => auth()->user()??false
                 // 'tasks'         => session('task_session')??false,
                 // 'currentDate'   => session('currentDate')??false,
                 // 'current_time'  => session('current_time')??false,
                 // 'current_task' => session('current_task')??[]
            
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
        // if(auth()->user()?->){

        // }
       
        return view('tardi',
        [
            'user'          => auth()->user()??false
            // 'tasks'         => session('task_session')??false,
            // 'currentDate'   => session('currentDate')??false,
            // 'current_time'  => session('current_time')??false,
            // 'current_task' => session('current_task')??[]
            
        ]);
        
    }

    public function show_tardi_staff()
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

    public function post_tardi()
    {        
        
        $tardis = Tardi::find(request('conforme'))??false; 
        
        if($tardis){
            return view('tardi_variance',
            [
                 'user'      => auth()->user()??false,
                 'tardis'    => $tardis,

                // 'tasks'         => session('task_session')??false,
                // 'currentDate'   => session('currentDate')??false,
                // 'current_time'  => session('current_time')??false,
                // 'current_task' => session('current_task')??[]
            
             ]);   

        }
        
    }

    public function conforme()
    {
        $user           = auth()->user()??false;    
        $Date           = Carbon::now('Asia/Kuala_Lumpur');   
        // $currentDate    = $Date->format('m/d/y');
        // $current_time   = $Date->format('Hi');

        $tasks = Tardi::find(request('tardis_id'))->update([
            
            'conforme'    =>  'sent',
            'con_date'    =>  $Date
        ]);      
        
        return redirect()->route('show_tardi')
           
        ;       
        
    }

    
}
