<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Biometric;
use App\Models\ManualShift;
use App\Models\Punch;
use App\Models\Schedule;

use App\Http\Controllers\AbsenceCalendarController;
use App\Http\Controllers\BiometricController;

use App\Models\Shift;
use App\Models\Update_bio;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class RawbioController extends Controller
{
    public function dtr()
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
            ->map(
                function ($user) use ($collection_of_dates, $holiday) {

                    $user = app()->call(
                        BiometricController::class . '@text_files_part_2',
                        [
                            'collection_of_dates' => $collection_of_dates,
                            'searched_user' => User::find($user->id),
                            'holiday' => $holiday
                        ]
                    );

                    return $user;
                }
            );

        return view('dtr', [

            'mappedUser' =>  $mappedArray

        ]);
    }
}
