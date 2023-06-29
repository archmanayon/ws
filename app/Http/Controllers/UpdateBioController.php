<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ManualShift;
use App\Models\Shift;
use App\Models\Biometric;
use App\Models\Rawbio;

use \Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Update_bio;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class UpdateBioController extends Controller
{
    public function new_bio($bio){        

        $bio_daily_array = Biometric::where(DB::raw('SUBSTRING(biotext, 1, 12)'),'=',$bio);
        $all_bio_punches = $bio_daily_array->selectRaw
            ('
                SUBSTRING(biotext, 1, 6) AS timecard,
                SUBSTRING(biotext, 1, 12) AS tc_date,
                SUBSTRING(biotext, 7, 6) AS date_bio,
                SUBSTRING(biotext, 13, 4) AS hour,
                SUBSTRING(biotext, 17, 1) AS in_out,
                id AS id
                ')
        ->get();
        
        $str_tc         = Str::limit($bio,6,'');
        $str_date       = substr($bio, 6, 6);
        $date_s         = Carbon::createFromFormat('mdy', $str_date);
        $date           = Carbon::parse($date_s);
        $searched_user  = User::where('timecard',  $str_tc)->get()[0];        

        $official = app()->call(ManualShiftController::class.'@official_',
            [
                // 'searched_user'     =>  User::where('timecard', $str_tc)->get(),
                'searched_user'     =>  $searched_user,
                'date'              =>  $date,
                'day'               =>  $date->format('l')
        ]);
        
        $updated_bio = Update_bio::where('time_card',$str_tc)
        ->where('date',$str_date)->get();           

        return view ('update_bio',[

            'old_bio'       =>  $all_bio_punches,

            'updated_bio'   =>  $updated_bio?? false,          

            'pref_bio'      =>  $updated_bio[0]??false ? $updated_bio : $all_bio_punches,
            'str_tc'        =>  $str_tc,
            'str_date'      =>  $str_date,
            // -------------------------------------------
            'searched_user' => $searched_user,
            // 'date'          => $date->format('Y-m-d'),
            // 'day'      => User::where('timecard',  $str_tc)->get()[0],
            'official' => $official

        ]);
    }

    public function store(Request $request, $bio)
    {
        $str_tc         = Str::limit($bio,6,'');
        $str_date       = substr($bio, 6, 6);
        $searched_user  = User::where('timecard',  $str_tc)->get()[0];

        $validated_new_bio = $request->validate([
                'new_bio.0' => 'required|string|min:4|max:4',
                'new_bio.1' => 'required|string|min:4|max:4',
                'new_bio.2' => 'nullable|string|min:4|max:4',
                'new_bio.3' => 'nullable|string|min:4|max:4'
            ]
            ,
            [
                'new_bio.0.required' => 'The first name field is required.',
                'new_bio.0.string' => 'The name field must be a string.',
                'new_bio.0.min' => 'The name field may be lesser than :min characters.',
                'new_bio.0.max' => 'The name field may be greater than :max characters.',

                'new_bio.1.required' => 'The first name field is required.',
                'new_bio.1.string' => 'The name field must be a string.',
                'new_bio.1.min' => 'The name field may be lesser than :min characters.',
                'new_bio.1.max' => 'The name field may be greater than :max characters.',

                'new_bio.2.string' => 'The name field must be a string.',
                'new_bio.2.min' => 'The name field may be lesser than :min characters.',
                'new_bio.2.max' => 'The name field may be greater than :max characters.',

                'new_bio.3.string' => 'The name field must be a string.',
                'new_bio.3.min' => 'The name field may be lesser than :min characters.',
                'new_bio.3.max' => 'The name field may be greater than :max characters.'
            ]
        );

        $new_input_date = [];

        $iteration = 0 ;

        foreach ($validated_new_bio['new_bio'] as $key => $value){

            if($value){

                $in_out = $iteration == 0 || $iteration == 2?"I":"O";

                // if display in arrays only
                $new_input_date[] = $iteration == 0 || $iteration == 2?
                                $str_tc.$str_date.$value."I":
                                $str_tc.$str_date.$value."O";          

                Update_bio::create([
                    'name'      => $searched_user->name,
                    'time_card' => $str_tc,
                    'date'      => $str_date,
                    'hour'      => $value,
                    'in_out'    => $in_out,
                    'biotext'   => $str_tc.$str_date.$value.$in_out,
                    'reason'    => request('reason_bio')
                ]);

                $iteration ++;
            }

        }

        
        return redirect('print')
            ->with('success_message', 'Updated Biometrics');
        
    }

    public function store_rawbio(Request $request, $rawbio)
    {
        $str_tc         = Str::limit($rawbio,6,'');
        $str_date       = substr($rawbio, 6, 6);
        $searched_user  = User::where('timecard',  $str_tc)->get()[0];

        $validated_new_bio = $request->validate([
                'new_bio.0' => 'nullable|string|min:4|max:4',
                'new_bio.1' => 'nullable|string|min:4|max:4',
                'new_bio.2' => 'nullable|string|min:4|max:4',
                'new_bio.3' => 'nullable|string|min:4|max:4'
            ]
            ,
            [
                'new_bio.0.string' => 'The name field must be a string.',
                'new_bio.0.min' => 'The name field may be lesser than :min characters.',
                'new_bio.0.max' => 'The name field may be greater than :max characters.',

                'new_bio.1.string' => 'The name field must be a string.',
                'new_bio.1.min' => 'The name field may be lesser than :min characters.',
                'new_bio.1.max' => 'The name field may be greater than :max characters.',

                'new_bio.2.string' => 'The name field must be a string.',
                'new_bio.2.min' => 'The name field may be lesser than :min characters.',
                'new_bio.2.max' => 'The name field may be greater than :max characters.',

                'new_bio.3.string' => 'The name field must be a string.',
                'new_bio.3.min' => 'The name field may be lesser than :min characters.',
                'new_bio.3.max' => 'The name field may be greater than :max characters.'
            ]
        );

        $new_input = [];

        $iteration = 0 ;

        foreach ($validated_new_bio['new_bio'] as $key => $value){

            if($value){

                $in_out = $iteration == 0 || $iteration == 2?"I":"O";

                // if display in arrays only
                $new_input[] = $iteration == 0 || $iteration == 2?
                                $str_tc.$str_date.$value."I":
                                $str_tc.$str_date.$value."O";          

                // Update_bio::create([
                //     'name'      => $searched_user->name,
                //     'time_card' => $str_tc,
                //     'date'      => $str_date,
                //     'hour'      => $value,
                //     'in_out'    => $in_out,
                //     'biotext'   => $str_tc.$str_date.$value.$in_out,
                //     'reason'    => request('reason_bio')
                // ]);

                $iteration ++;
            }

        }

        return redirect('rawbio/'.$str_tc.$str_date)
            ->with([
                // 'user_session' => $user
                // 'task_session' => $task,
                // 'currentDate'  => $currentDate,
                // 'current_time' => $current_time,
                // 'current_task' => $user->tasks
                // 'current_task' => $user->tasks
                'new_input'     => $new_input??false
            ])
        ;
        
    }
}
