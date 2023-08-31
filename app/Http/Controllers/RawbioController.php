<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Biometric;
use App\Models\ManualShift;
use App\Models\Punch;
use App\Models\Schedule;
use App\Exports\RawbioExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Http\Controllers\AbsenceCalendarController;
use App\Http\Controllers\BiometricController;

use App\Models\Shift;
use App\Models\Update_bio;
use App\Models\Setup;

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
        $payroll_start  = '2023-07-16';
        $payroll_end    = Carbon::create(Setup::find(2)->date) ?? 0;

        $holiday = array(
            "01-05-23", "01-06-23",
            "02-24-23", "02-25-23",
            "04-06-23", "04-07-23",
            "04-08-23", "04-10-23", "05-01-23",
            "04-21-23", "06-12-23", "06-28-23"
        );

        $searched_user = auth()->user()??false;
       

        $start_date = request('start_date')?
        (request('start_date') < $payroll_start ? $payroll_start : request('start_date')??0):
        0
        ;

                        
        $end_date = request('end_date') > $payroll_end->format('Y-m-d') ?
                $payroll_end->format('Y-m-d') : request('end_date')??$payroll_end->format('Y-m-d');  

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
            'user'          => $searched_user,
            'payroll_start' => $start_date,
            'payroll_end'   => $end_date


        ]);
    }

    public function my_dtr_pdf($selected_dates)
    {
        $searched_user = auth()->user()??false;

        $holiday = array(
            "01-05-23", "01-06-23",
            "02-24-23", "02-25-23",
            "04-06-23", "04-07-23",
            "04-08-23", "04-10-23", "05-01-23",
            "04-21-23", "06-12-23", "06-28-23"
        );

        $split = explode('to', $selected_dates);
        $start_date = $split[0] ?? 0;
        $end_date = $split[1] ?? 0;
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

        return $pdf->download("{$searched_user->username}|{$start_date} to {$end_date}.pdf");
        // return $pdf->stream();


        // return view('pdf.my_dtr_pdf', [

        //     'mapped_days'   =>  $mappedArray,

        //     // for choices of employees only
        //     'user'          => $searched_user

        // ]);


    }

    public function my_dtr_exel($selected_dates)
    {
        $searched_user = auth()->user()??false;

        $split = explode('to', $selected_dates);
        $start_date = $split[0] ?? 0;
        $end_date = $split[1] ?? 0;

        // return Excel::download(new RawbioExport(), 'dtr.xlsx');
        return Excel::download(new RawbioExport($selected_dates),
            "dtr|{$searched_user->username}|{$start_date} to {$end_date}.xlsx");


        // return view('pdf.my_dtr_pdf', [

        //     'mapped_days'   =>  $mappedArray,

        //     // for choices of employees only
        //     'user'          => $searched_user

        // ]);


    }
}
