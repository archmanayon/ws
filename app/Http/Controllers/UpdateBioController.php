<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Biometric;
use App\Models\Update_bio;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Http\Request;

class UpdateBioController extends Controller
{
    public function new_bio($bio){        

        $bio_daily_array = Biometric::where(DB::raw('SUBSTRING(biotext, 1, 12)'), '=',  $bio);                

        $all_bio_punches = $bio_daily_array->selectRaw
            ('                
                SUBSTRING(biotext, 1, 6) AS timecard,
                SUBSTRING(biotext, 7, 6) AS date_bio,
                SUBSTRING(biotext, 13, 4) AS hour,
                SUBSTRING(biotext, 17, 1) AS in_out,
                id AS id
                ')
        ->get();         

        return view ('update_bio',[

            'old_bio' => $all_bio_punches

        ]);
    }

    public function store(Request $request, $bio)
    {
        // $new_bio = $request->input('new_bio')?? false;

        $new_bio = $request->validate([
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

        $updated_bio = Update_bio::all();

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

        $iteration = 0 ;

        foreach ($new_bio['new_bio'] as $key => $value){ 

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

            $iteration ++;

        }
               
        return view ('update_bio',[

            'old_bio' => $old_bio,

            'new_bio' =>$new_bio,
            
            'am' => $new_input_date,

            'updated_bio' => $updated_bio
        ]);
    }
}
