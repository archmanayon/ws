<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Biometric;
use \Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Http\Request;

class UpdateBioController extends Controller
{
    public function new_bio($bio){     
        
        $new_am_in = request(['new_am_in'])??false;

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
        $request = $request->input('new_bio')?? false;

        $new_bio = [];

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
        $iteration = 0 ;
        foreach ($request as $new_punch){ 
            $iteration == 0 || $iteration == 2?
            $new_bio[] =  $all_bio_punches[0]->timecard.$all_bio_punches[0]->date_bio.$new_punch."I":
            $new_bio[] =  $all_bio_punches[0]->timecard.$all_bio_punches[0]->date_bio.$new_punch."O";
            $iteration ++;
        }
               
        return view ('update_bio',[

            'old_bio' => $all_bio_punches,
            'new_bio' => $request,
            'new_bio' =>$new_bio
        ]);
    }
}
