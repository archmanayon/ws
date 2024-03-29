<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ManualShift;
use App\Models\Shift;
use App\Models\Punch;
use App\Models\Schedule;
use App\Models\Biometric;
use App\Models\Rawbio;
use App\Models\Update_bio;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Http\Request;

class BiometricController extends Controller
{

    private  $holiday = array( "08-21-23", "08-28-23", "09-09-23", "10-30-23", "10-31-23", "11-01-23", "11-02-23", "11-27-23", "12-08-23", "12-18-23", "12-19-23", "12-20-23", "12-21-23", "12-22-23", "12-23-23", "12-25-23", "12-26-23", "12-27-23", "12-28-23", "12-29-23", "12-30-23", "01-01-24", "01-02-24", "01-03-24",);

    public function text_files($collection_of_dates, $searched_user, $holiday)
    {

        $mappedArray = $collection_of_dates
            ->map(function ($date) use ($searched_user, $holiday) {

                $date = Carbon::parse($date);

                $d_date = $date->format('m-d-y');

                $day = $date->format('l');

                $am_in = $day . "_am_in";

                $pm_in = $day . "_pm_in";

                $ten_min_allowance = 0.17;
                // $ten_min_allowance = 0;


                //---to choose between 'official shift' and 'manual shift'
                $official = app()->call(
                    ManualShiftController::class . '@official_',
                    [
                        'searched_user'     =>  $searched_user,
                        'date'              =>  $date,
                        'day'               =>  $day
                    ]
                );

                //---to extract punch 'object' from bio text files
                $punch = app()->call(
                    ExtractBioController::class . '@extract_bio',
                    [
                        'searched_user'     => $searched_user,
                        'date'              => $date,
                        'official'          => $official
                    ]
                );
                $punches = array(
                    $punch->am_in?$searched_user->timecard.$date->format('mdy').$punch->am_in.'I':'',
                    $punch->am_out?$searched_user->timecard.$date->format('mdy').$punch->am_out.'O':'',
                    $punch->pm_in?$searched_user->timecard.$date->format('mdy').$punch->pm_in.'I':'',
                    $punch->pm_out?$searched_user->timecard.$date->format('mdy').$punch->pm_out.'O':''
                );

                // ________________________________________________________

                return (object) [
                    'user' => $searched_user,
                    // 'student_id'=> $searched_user->student_id,
                    // 'name'=> $searched_user->name,
                    // 'timecard'=> $searched_user->timecard,
                    'date'=> $d_date,
                    'punch' => $punches
                ];


            })->toArray();

        return $mappedArray;
    }

    public function text_files_part_2($collection_of_dates, $searched_user, $holiday)
    {

        // ----------------orig bio----------------------------------
        $orig_bio = Rawbio::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $searched_user->timecard??false);
        $sub_orig_bio = $orig_bio->selectRaw('
            SUBSTRING(biotext, 7, 6) AS date,
            SUBSTRING(biotext, 13, 4) AS hour,
            SUBSTRING(biotext, 17, 1) AS in_out,
            SUBSTRING(biotext, 1, 17) AS biotext,
            SUBSTRING(punchtype_id, 1,1) AS punchtype_id
        ');

         // querry fron shcp bio
         if($searched_user->punches??false){
            
            $sub_shcp_punch =  $searched_user->punches()->select('date', 'hour', 'in_out', 'biotext', 'punchtype_id'); 

            $merged = $sub_orig_bio->union($sub_shcp_punch)->get()->sortBy('biotext');  

         } else{

            $merged = $sub_orig_bio->get()->sortBy('biotext');  
         }         

        $mappedArray = $collection_of_dates
            ->map(function ($date) use ($searched_user, $holiday, $merged) {

                $date = Carbon::parse($date);

                $d_date = $date->format('m-d-y');

                $day = $date->format('l');

                //---NOT to choose between 'official shift' and 'manual shift'[], this is just to provide for paramereter
                $official = app()->call(ManualShiftController::class.'@official_',
            [
                'searched_user'     =>  $searched_user,
                'date'              =>  $date,
                'day'               =>  $day
            ]);

                //---to extract punch 'object' from bio text files
                $punches = app()->call(
                    ExtractBioController::class . '@extract_bio_textfiles',
                    [
                        'searched_user'     => $searched_user,
                        'date'              => $date,
                        'official'         => $official,
                        'merged'    => $merged
                    ]
                );

                // ________________________________________________________

                return $punches;

            })->toArray();

        return  $mappedArray;

    }

    public function raw_bio_text()
    {
        $holiday = $this->holiday; 

        $start_date = request('start_date')?? 0;
        $end_date = request('end_date')?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection_of_dates = collect($dates);
        $count_dates = $period->count();

        $users = User::where(function ($query) {
            $query->where('role_id', '=', 2)->orWhere('role_id', '=', 5);
        })->get();

        $mapped_users = collect($users->where('active',true)->sortBy('name'))
        ->map(function ($searched_user) use ($collection_of_dates) {
            
            // ---------------- orig bio----------------------------------
            $orig_bio = Rawbio::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $searched_user->timecard??false);
            $sub_orig_bio = $orig_bio->selectRaw('
                SUBSTRING(biotext, 7, 6) AS date,
                SUBSTRING(biotext, 13, 4) AS hour,
                SUBSTRING(biotext, 17, 1) AS in_out,
                SUBSTRING(biotext, 1, 17) AS biotext,
                SUBSTRING(punchtype_id, 1,1) AS punchtype_id
            ');

            // ---------------- querry fron shcp bio
            if($searched_user->punches??false ){
                
                $sub_shcp_punch =  $searched_user->punches()->select('date', 'hour', 'in_out', 'biotext', 'punchtype_id'); 

                $merged = $sub_orig_bio->union($sub_shcp_punch)->get()->sortBy('biotext');  

            } else{

                $merged = $sub_orig_bio->get()->sortBy('biotext');  
            }  
            // dd($merged[0]->date);
            // dd(Carbon::parse($collection_of_dates->first())->format('mdy'));          

            $mapped_dates = $collection_of_dates
            ->map(function ($date) use ($searched_user, $merged) {

                $date = Carbon::parse($date);

                $d_date = $date->format('mdy');

                // ----------------Updated bio ----------------------------------

                $updated_bio = $searched_user->update_bios->where('active',1)->where('date', $d_date);

                return (object) [
                    'punch'        => $merged->where('date', '=', $d_date)->values(),
                    'updated_bio'  => $updated_bio->values(),
                    'user'         => $searched_user
                ];

            })->toArray();

            return $mapped_dates;
        });

        return view('raw_bio_text', [

            'mappedUser' =>  $mapped_users

        ]);
    }
}
