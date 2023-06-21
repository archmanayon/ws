<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ManualShift;
use App\Models\Shift;
use App\Models\Punch;
use App\Models\Schedule;
use App\Models\Biometric;
use Carbon\Carbon;

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
}
