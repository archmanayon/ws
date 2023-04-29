<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Biometric;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Update_bio;


use Illuminate\Http\Request;

class UpdateBioController extends Controller
{
    public function new_bio($bio){   
                
        // $updated_biometric = Update_bio::all()->where('time_card','505180')->where('date','030323')[3]->hour;
        
        $bio_daily_array = Biometric::where(DB::raw('SUBSTRING(biotext, 1, 12)'), '=',  $bio);                

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

        $updated_bio = Update_bio::where('time_card',$all_bio_punches[0]->timecard)
            ->where('date',$all_bio_punches[0]->date_bio)->get()??false;

        $find =  $updated_bio[0]->time_card. $updated_bio[0]->date;

        $pref_bio = $all_bio_punches->pluck('tc_date')[0] == $find?
            $updated_bio?? false : $all_bio_punches;
        // $pref_bio = in_array('505180021023',$pref_bio);
        // $pref_bio = in_array($find,$all_bio_punches->pluck('tc_date'));
        
        return view ('update_bio',[

            'old_bio' => $all_bio_punches,

            'updated_bio' => $updated_bio?? false,
            
            'pref_bio' => $pref_bio
            

        ]);
    }

    public function store(Request $request, $bio)
    {
        // $new_bio = $request->input('new_bio')?? false;

        $validated_new_bio = $request->validate([
            'new_bio.0' => 'required|string|min:4|max:4',
            'new_bio.1' => 'required|string|min:4|max:4',
            'new_bio.2' => 'nullable|string|min:4|max:4',
            'new_bio.3' => 'nullable|string|min:4|max:4'
            ]
            ,        [            
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

        // dd($new_bio);        

        $new_input_date = [];        

        $bio_daily_array = Biometric::where(DB::raw('SUBSTRING(biotext, 1, 12)'), '=',  $bio);
        $old_bio = $bio_daily_array->selectRaw
            ('                
                SUBSTRING(biotext, 1, 6) AS timecard,
                SUBSTRING(biotext, 7, 6) AS date_bio,
                SUBSTRING(biotext, 13, 4) AS hour,
                SUBSTRING(biotext, 17, 1) AS in_out,
                id AS id
                ')
        ->get();  

        // $updated_biometric = Update_bio::all()->where('time_card', '505180')->where('hour', '030323')()??false;

        // $updated_biometric = Update_bio::all()->where('time_card','505180')->where('date','030323')()??false;

        $iteration = 0 ;

        foreach ($validated_new_bio['new_bio'] as $key => $value){ 

            if($value){
                $in_out = $iteration == 0 || $iteration == 2?"I":"O";

                // if display in arrays only
                $new_input_date[] = $iteration == 0 || $iteration == 2?
                                $old_bio[0]->timecard.$old_bio[0]->date_bio.$value."I":
                                $old_bio[0]->timecard.$old_bio[0]->date_bio.$value."O";
            }

            Update_bio::create([
                'time_card' => $old_bio[0]->timecard,
                'date'      => $old_bio[0]->date_bio,
                'hour'      => $value,
                'in_out'    => $in_out,
                'biotext'   => $old_bio[0]->timecard.$old_bio[0]->date_bio.$value.$in_out,
                'reason'    => request('reason_bio')
            ]);
            if( $iteration <= 4 ){
                $iteration ++;
            }
            

        }
               
        return view ('update_bio',[
            
            'old_bio' => $old_bio,

            'new_bio' =>$new_bio,
            
            'am' => $new_input_date
            
            // 'updated_biometric' =>  $updated_biometric?? false
        ]);
    }
}
