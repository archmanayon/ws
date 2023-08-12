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
use Barryvdh\DomPDF\Facade\Pdf;

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

        $searched_user = User::find(request('find_user'))??false;

        $start_date = request('start_date') ?? 0;
        $end_date = request('end_date') ?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection_of_dates = collect($dates);
        $count_dates = $period->count();

        $mappedArray = app()->call(
            BiometricController::class . '@text_files_part_2',
            [
                'collection_of_dates' => $collection_of_dates,
                'searched_user' => $searched_user,
                'holiday' => $holiday
            ]
        );

        return view('dtr', [

            'mapped_days' =>  $mappedArray,

            // for choices of employees only
            'users'        => User::all()->where('active', true)

        ]);
    }

    public function my_dtr()
    {
        $holiday = array(
            "01-05-23", "01-06-23",
            "02-24-23", "02-25-23",
            "04-06-23", "04-07-23",
            "04-08-23", "04-10-23", "05-01-23",
            "04-21-23", "06-12-23", "06-28-23"
        );

        $searched_user = auth()->user()??false;

        $start_date = request('start_date') ?? 0;
        $end_date = request('end_date') ?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection_of_dates = collect($dates);
        $count_dates = $period->count();

        $mappedArray = app()->call(
            BiometricController::class . '@text_files_part_2',
            [
                'collection_of_dates' => $collection_of_dates,
                'searched_user' => $searched_user,
                'holiday' => $holiday
            ]
        );

        return view('my_dtr', [

            'mapped_days'   =>  $mappedArray,

            // for choices of employees only
            'user'          => $searched_user


        ]);
    }

    public function my_dtr_pdf()
    {
        $holiday = array(
            "01-05-23", "01-06-23",
            "02-24-23", "02-25-23",
            "04-06-23", "04-07-23",
            "04-08-23", "04-10-23", "05-01-23",
            "04-21-23", "06-12-23", "06-28-23"
        );

        $searched_user = auth()->user()??false;

        $start_date = request('st') ?? 0;
        $end_date = request('en') ?? 0;
        $period = CarbonPeriod::create($start_date, $end_date);
        $dates = $period->toArray();
        $collection_of_dates = collect($dates);
        $count_dates = $period->count();

        $mappedArray = app()->call(
            BiometricController::class . '@text_files_part_2',
            [
                'collection_of_dates' => $collection_of_dates,
                'searched_user' => $searched_user,
                'holiday' => $holiday
            ]
        );

        $pdf = Pdf::loadview('pdf.my_dtr_pdf', [

            'mapped_days'   =>  $mappedArray,
            'user'          => $searched_user,


        ]);
        
        // return $pdf;

        return $pdf->download("{$searched_user->username}|{$start_date} to {$end_date}.pdf");

        // return view('pdf.my_dtr_pdf', [

        //     'mapped_days'   =>  $mappedArray,

        //     // for choices of employees only
        //     'user'          => $searched_user

        // ]);


    }
}
