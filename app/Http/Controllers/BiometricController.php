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

use Carbon\Carbon;
use Carbon\CarbonPeriod;

use Illuminate\Http\Request;

class BiometricController extends Controller
{
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

        $mappedArray = $collection_of_dates
            ->map(function ($date) use ($searched_user, $holiday) {

                $date = Carbon::parse($date);

                $d_date = $date->format('m-d-y');

                $day = $date->format('l');

                //---to choose between 'official shift' and 'manual shift'

                //---to extract punch 'object' from bio text files
                $punches = app()->call(
                    ExtractBioController::class . '@extract_bio_part_two',
                    [
                        'searched_user'     => $searched_user,
                        'date'              => $date
                    ]
                );

                // ________________________________________________________

                return $punches;

            })->toArray();

        return (object) [
            
            'punches' => $mappedArray
        ];

    }

    public function raw_bio_text()
    {
        $holiday = array(
            "01-05-23", "01-06-23",
            "02-24-23", "02-25-23",
            "04-06-23", "04-07-23",
            "04-08-23", "04-10-23", "05-01-23",
            "04-21-23", "06-12-23", "06-28-23"
        );

        $start_date = request('start_date') ?? 0;
        $end_date = request('end_date') ?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection_of_dates = collect($dates);
        $count_dates = $period->count();

        $mappedArray = collect(User::all()->where('active', true)->where('role_id', 2))
        ->map(function ($user) use ($collection_of_dates) {

            $user = $collection_of_dates
                ->map(function ($date) use ($user) {

                    $date = Carbon::parse($date);

                    $d_date = $date->format('m-d-y');

                    $day = $date->format('l');

                    $punches = Rawbio::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $user->timecard)
                        ->where(DB::raw('SUBSTRING(biotext, 7, 6)'), '=', $date->format('mdy')) ?? false;

                    $rawbio = $punches->selectRaw('
                        SUBSTRING(biotext, 1, 6) AS timecard,
                        SUBSTRING(biotext, 7, 6) AS date,
                        SUBSTRING(biotext, 13, 4) AS hour,
                        SUBSTRING(biotext, 17, 1) AS in_out,
                        SUBSTRING(biotext, 1, 17) AS text
                        ')
                        ->get();

                    return (object) [
                        'punch' => $rawbio,
                        'user'  => $user
                    ];
                })->toArray();

            return $user;
        });

        return view('raw_bio_text', [

            'mappedUser' =>  $mappedArray

        ]);
    }
}
