<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Punch;
use Carbon\Carbon;
use App\Http\Controllers\AuthenticatedSessionController;

class PunchController extends Controller
{
    public function show()
    {
        $Date           = Carbon::now('Asia/Kuala_Lumpur');                
        $currentDate    = $Date->format('mdy');
        $current_time   = $Date->format('Hi');       
        $searched_user  = User::find(auth()->user()->id);
        $in_out         = $punches->pluck('in_out')->last() === 'I' ? 'O' : 'I';



        return view ('shcp',[

            'date'          => $Date,
            'currentDate'   => $currentDate,
            'current_time'  => $current_time,            
            'punches_today' => $searched_user->punches->where('date', $currentDate),               
            'end' => $punches->pluck('in_out')
        ]);
        
    }
    public function store()
    {
        $Date           = Carbon::now('Asia/Kuala_Lumpur');                
        $currentDate    = $Date->format('mdy');
        $current_time   = $Date->format('Hi');       
        $searched_user  = User::find(auth()->user()->id);
        $punches        = Punch::where('user_id', auth()->user()->id)
                            ->where('date', $currentDate)->get();
        $in_out         = $punches->pluck('in_out')->last() === 'I' ? 'O' : 'I';    

        Punch::create([
            'user_id'   =>  $searched_user->id,
            'date'     =>   $currentDate,
            'hour'      =>  $current_time,
            'in_out'    =>  $in_out,
            'biotext'   =>  $searched_user->timecard.$currentDate.$current_time.$in_out
                    ]);        
     
        auth()->logout();
        return redirect()->route('show_punches')
            ->with('success_message', 'You Have Punched In');
        
    }
}
