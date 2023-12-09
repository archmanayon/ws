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
    private  $holiday = array( "08-21-23", "08-28-23", "09-09-23", "10-30-23", "10-31-23", "11-01-23", "11-02-23", "11-27-23", "12-08-23");

    public function dtr()
    {
        $users  = User::all()->where('active', true)->sortBy('name');        

        $searched_user = $users->where('id', request('find_user'))->first()??false;

        // $searched_user = User::find(request('find_user'))??false;

         $holiday = $this->holiday;

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
            'users'        => $users
        ]);
    }

    public function my_dtr()
    {
        $bio_start  = Carbon::create(Setup::find(3)->date)->format('Y-m-d');
        $bio_end    = Carbon::create(Setup::find(4)->date)->format('Y-m-d');

         $holiday = $this->holiday;

        $searched_user = auth()->user()??false;
        
        $start_date = request('start_date')?
        (request('start_date') < $bio_start ? $bio_start : request('start_date')):
        Carbon::create(Setup::find(1)->date)->format('Y-m-d');

        $end_date = request('end_date')?
        (request('end_date') > $bio_end ? $bio_end : request('end_date')):
        Carbon::create(Setup::find(2)->date)->format('Y-m-d');

        $period = CarbonPeriod::create($start_date??false, $end_date??false);

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

         $holiday = $this->holiday;

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
