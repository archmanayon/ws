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

                'tardis' => $tardis

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

        return view('tardi',
        [
            'user'          => auth()->user()??false
        ]);

    }

    public function post_tardi()
    {

        $tardis = Tardi::find(request('conforme'))??false;

        if($tardis){

            return view('tardi_variance',
            [
                'tardis'    => $tardis

                // 'tasks'         => session('task_session')??false,
                // 'currentDate'   => session('currentDate')??false,
                // 'current_time'  => session('current_time')??false,
                // 'current_task' => session('current_task')??[]

             ]);
        }
    }

    public function conforme()
    {
        $Date           = Carbon::now('Asia/Kuala_Lumpur');
        // $currentDate    = $Date->format('m/d/y');
        // $current_time   = $Date->format('Hi');

        Tardi::find(request('tardis_id'))?->update([

            'conforme'    =>  'sent',
            'con_date'    =>  $Date
        ]);

        return redirect()->route('show_tardi')

        ;

    }

    // 01
    public function tardi_group()
    {
        return view('tardi_group',
            [
                'user'          => auth()->user() ?? false,
                'group'         => auth()->user()->heads[0]->users
                // 'tasks'         => session('task_session')??false,
                // 'currentDate'   => session('currentDate')??false,
                // 'current_time'  => session('current_time')??false,
                // 'current_task' => session('current_task')??[]

            ]
        );
    }

    // 02

    public function staff_variance()
    {

        $tardis = Tardi::find(request('pre_address'))??false;

        if($tardis){

            return view('tardi_staff',
            [

                'tardis' => $tardis
                 // 'tasks'         => session('task_session')??false,
                 // 'currentDate'   => session('currentDate')??false,
                 // 'current_time'  => session('current_time')??false,
                 // 'current_task' => session('current_task')??[]

            ]);

        } else {

            return redirect()->route('show_tardi_group')
            // ->with([
                // 'user_session' => $user

            // ])
            ;
        }

    }

    public function tardi_staff()
    {

        $tardis = Tardi::find(request('pre_address')) ?? false;

        if ($tardis) {

            return view('tardi_staff',
                [
                    'tardis' => $tardis
                    // 'tasks'         => session('task_session')??false,
                    // 'currentDate'   => session('currentDate')??false,
                    // 'current_time'  => session('current_time')??false,
                    // 'current_task' => session('current_task')??[]

                ]
            );
        } else {

            return redirect()->route('show_tardi_group')
                // ->with([
                // 'user_session' => $user

                // ])
            ;
        }
    }

    public function post_address()
    {
        $user           = auth()->user()??false;
        $Date           = Carbon::now('Asia/Kuala_Lumpur');

        $sige = Tardi::find(request('post_address'))->update([

            'head_sig'  =>  $user->username,
            'sig_date'  =>  $Date,
            'remarks'   =>  request('h_remarks'),

        ]);

        return redirect()->route('show_tardi_group')

        ;


    }



}
