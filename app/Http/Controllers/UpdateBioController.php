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

    public function post_new_bio(Request $request, $bio)
    {
        $requested = $request->input('new_bio')?? false;

        $new_bio = [];

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
        foreach ($requested as $new_punch){ 

            $new_input_date = $iteration == 0 || $iteration == 2?
                                $old_bio[0]->timecard.$old_bio[0]->date_bio.$new_punch."I":
                                $old_bio[0]->timecard.$old_bio[0]->date_bio.$new_punch."O";

            // $new_input_date->validate(['required', 'string', 'unique']);

            Update_bio::create([

                'biotext' => $new_input_date,
                'reason' => request('reason_bio')
            ]);

            // $iteration == 0 || $iteration == 2?
            // $new_bio[] =  $old_bio[0]->timecard.$old_bio[0]->date_bio.$new_punch."I":
            // $new_bio[] =  $old_bio[0]->timecard.$old_bio[0]->date_bio.$new_punch."O";

            $iteration ++;

        }
               
        return view ('update_bio',[

            'old_bio' => $old_bio,
            // 'new_bio' => $request,
            'new_bio' =>$new_bio
        ]);
    }
}
