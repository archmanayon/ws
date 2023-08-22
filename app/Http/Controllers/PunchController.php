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

class PunchController extends Controller
{
    public function show(Request $request)
    {
        $Date           = Carbon::now('Asia/Kuala_Lumpur');
        $currentDate    = $Date->format('mdy');
        $current_time   = $Date->format('Hi');
        $usc_id         = session('usc_id')??false;
        $searched_user  = User::where('student_id', $usc_id)->first();
        $punches        = $usc_id ? $searched_user->punches->where('date', $currentDate) : false;
        $in_out         = $punches?($punches->pluck('in_out')->last() === 'I' ? 'O' : 'I'):false;

        return view('shcp',[

            'employee'      => $searched_user ?? false,
            'date_'         => $Date,
            'currentDate'   => $currentDate,
            'current_time'  => $current_time,
            'usc_id'        => $usc_id,
            'punches_today' => $punches,
            'end'           => $usc_id? $searched_user->timecard.$currentDate.$current_time.$in_out:false,
            'in_out'        => $in_out
            // 'ip'            => $request->ip()

        ]);

    }

    public function store()
    {
        $validatedData = request()->validate(
            [
                'student_id' => ['required','string','min:7', 'max:8',Rule::exists('users', 'student_id')],

                'punch_pw' => ['required', 'max:255', 'min:7']

            ]
            // ,
                // [
                //     'student_id.required'   => 'The field is required.',
                //     'student_id.string'     => 'Must be a string.',
                //     'student_id.min'        => 'The field may be lesser than :min characters.',
                //     'student_id.max'        => 'The field may be greater than :max characters.'
                // ]
                // ,
                // [
                //     'punch_pw.required'   => 'The field is required.',
                //     'punch_pw.string'     => 'Must be a string.'
            // ]

        );

        $Date           = Carbon::now('Asia/Kuala_Lumpur');
        $currentDate    = $Date->format('mdy');
        $current_time   = $Date->format('Hi');
        $usc_id         = $validatedData['student_id'];
        $searched_user  = User::where('student_id', $usc_id)->first();
        $punches        = $usc_id ? $searched_user->punches->where('date', $currentDate) : false;
        $in_out         = $punches->pluck('in_out')->last() === 'I' ? 'O' : 'I';

        // auth()->logout();

        if($validatedData['student_id'] &&
            Hash::check($validatedData['punch_pw'], $searched_user->password))
            {
                Punch::create([
                    'user_id'   =>  $searched_user->id,
                    'date'     =>   $currentDate,
                    'hour'      =>  $current_time,
                    'in_out'    =>  $in_out,
                    'biotext'   =>  $searched_user->timecard.$currentDate.$current_time.$in_out,
                    'punchtype_id' => 8
                    // 'ip'        => request('i_p')?? 0

                ]);

                return redirect()->route('show_punches')
            ->with('usc_id', $validatedData['student_id']);

        }

        throw ValidationException::withMessages([

            // erro ani gisulod sa errors->all() or erros->any()
            'error' => 'Could not be verified'

        ]);

    }

    // public function show_()
    // {
    //     $Date           = Carbon::now('Asia/Kuala_Lumpur');
    //     $currentDate    = $Date->format('mdy');
    //     $current_time   = $Date->format('Hi');
    //     $usc_id         = session('usc_id')??false;
    //     $searched_user  = User::where('student_id', $usc_id)->first();
    //     $punches        = $usc_id ? $searched_user->punches->where('date', $currentDate) : false;
    //     $in_out         = $punches?($punches->pluck('in_out')->last() === 'I' ? 'O' : 'I'):false;

    //     return view('shcp_',[

    //         'employee'      => $searched_user ?? false,
    //         'date_'         => $Date,
    //         'currentDate'   => $currentDate,
    //         'current_time'  => $current_time,
    //         'usc_id'        => $usc_id,
    //         'punches_today' => $punches,
    //         'end'           => $usc_id? $searched_user->timecard.$currentDate.$current_time.$in_out:false,
    //         'in_out'        => $in_out

    //     ]);

    // }

    public function show_()
    {
        $tc = request('tc')??false;
        $im = request('im')??false;

        $Date           = Carbon::now('Asia/Kuala_Lumpur');
        $currentDate    = $Date->format('mdy');
        $current_time   = $Date->format('Hi');
        $usc_id         = session('usc_id')??false;
        $searched_user  = User::where('timecard', $tc)->first();
        $punches        = $usc_id ? $searched_user->punches->where('date', $currentDate) : false;
        $in_out         = $punches?($punches->pluck('in_out')->last() === 'I' ? 'O' : 'I'):false;

        

        return view('shcp_',[

            'employee'      => $searched_user ?? false,
            'date_'         => $Date,
            'currentDate'   => $currentDate,
            'current_time'  => $current_time,
            'usc_id'        => $usc_id,
            'punches_today' => $punches,
            'end'           => $usc_id? $searched_user->timecard.$currentDate.$current_time.$in_out:false,
            'in_out'        => $in_out

        ]);

    }

    public function store_()
    {
        $validatedData = request()->validate(
            [
                'student_id' => ['required','string','min:7', 'max:8',Rule::exists('users', 'student_id')],

                // 'punch_pw' => ['required', 'max:255', 'min:7']

            ]
            // ,
                // [
                //     'student_id.required'   => 'The field is required.',
                //     'student_id.string'     => 'Must be a string.',
                //     'student_id.min'        => 'The field may be lesser than :min characters.',
                //     'student_id.max'        => 'The field may be greater than :max characters.'
                // ]
                // ,
                // [
                //     'punch_pw.required'   => 'The field is required.',
                //     'punch_pw.string'     => 'Must be a string.'
            // ]

        );

        $Date           = Carbon::now('Asia/Kuala_Lumpur');
        $currentDate    = $Date->format('mdy');
        $current_time   = $Date->format('Hi');
        $usc_id         = $validatedData['student_id'];
        $searched_user  = User::where('student_id', $usc_id)->first();
        $punches        = $usc_id ? $searched_user->punches->where('date', $currentDate) : false;
        $in_out         = $punches->pluck('in_out')->last() === 'I' ? 'O' : 'I';

        // auth()->logout();

        if($validatedData['student_id'] 
            // && Hash::check($validatedData['punch_pw'], $searched_user->password)
            )
            {
                Punch::create([
                    'user_id'   =>  $searched_user->id,
                    'date'     =>   $currentDate,
                    'hour'      =>  $current_time,
                    'in_out'    =>  $in_out,
                    'biotext'   =>  $searched_user->timecard.$currentDate.$current_time.$in_out,
                    'punchtype_id' => 8
                    // 'ip'        => request('i_p')?? 0

                ]);

                return redirect()->route('show_punches_')
            ->with('usc_id', $validatedData['student_id']);
        }

        throw ValidationException::withMessages([

            // erro ani gisulod sa errors->all() or erros->any()
            'error' => 'Could not be verified'

        ]);

    }
}
