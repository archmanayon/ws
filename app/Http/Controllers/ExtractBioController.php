<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Shift;
use App\Models\Biometric;
use App\Models\Rawbio;
use App\Models\Update_bio;
use App\Models\ManualShift;
use \Carbon\Carbon;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class ExtractBioController extends Controller
{
    public function extract_bio ($searched_user, $date, $official)
    {
        $str_tc     = $searched_user->timecard;
        $str_date   = $date->format('mdy');

        // A. ----------------updated bio----------------------------------

                // $updated_bio = Update_bio::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $searched_user->timecard)
                //                 ->where(DB::raw('SUBSTRING(biotext, 7, 6)'), '=', $date->format('mdy'))??false;

                // $sub_updated_bio = $updated_bio->selectRaw
                //     ('
                //         SUBSTRING(biotext, 1, 6) AS timecard,
                //         SUBSTRING(biotext, 1, 12) AS tc_date,
                //         SUBSTRING(biotext, 7, 6) AS date,
                //         SUBSTRING(biotext, 13, 4) AS hour,
                //         SUBSTRING(biotext, 17, 1) AS in_out,
                //         id AS id
                //         ')
                // ->get();

        // B. _______________________
            // $sub_updated_bio = Update_bio::where('time_card', $str_tc)->where('date', $str_date)->get();

        // c. ----------------updated bio 3rd style----------------------------------
        $sub_updated_bio = $searched_user->update_bios->where('date', $str_date);

        // ----------------orig bio----------------------------------

        // $orig_bio = Biometric::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $searched_user->timecard)
        //                 ->where(DB::raw('SUBSTRING(biotext, 7, 6)'), '=', $date->format('mdy'))??false;

        $orig_bio = Rawbio::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $searched_user->timecard)
                        ->where(DB::raw('SUBSTRING(biotext, 7, 6)'), '=', $date->format('mdy'))??false;

        $sub_orig_bio = $orig_bio->selectRaw
            ('
                SUBSTRING(biotext, 7, 6) AS date_bio,
                SUBSTRING(biotext, 13, 4) AS hour,
                SUBSTRING(biotext, 17, 1) AS in_out
                ')
        ->get();

        // A---------------------------------------------------------
            // if( $sub_updated_bio->pluck('tc_date')->contains( $str_tc.$str_date))

        // C. _______________________________________________________

        if($sub_updated_bio->pluck('date')->contains( $date->format('mdy')))
        {
            // $all_bio_punches = $sub_updated_bio;

            $all_bio_punches = $sub_updated_bio->pluck('hour')
                ->map(function ($hour, $in_out){ return (object) ['hour' => $hour, 'in_out' => $in_out];
                    });

        } else {

            $all_bio_punches = $sub_orig_bio;
        }

        $bio_am_in = $all_bio_punches[0]->hour??false;
        $bio_am_out = $all_bio_punches[1]->hour??false;

        $bio_pm_in = $all_bio_punches[2]->hour ??  $bio_am_in;
        $bio_pm_out = $all_bio_punches[3]->hour ?? ($all_bio_punches[2]->hour??false?false:
                                                    ($all_bio_punches[1]->hour??false));

        // uses 'official_am_in and out generated by validator controller:manualshift->official_
        // to assign punches from biotext file properly, to be processed for reporting
        $allowance_for_am = round((strtotime($bio_am_in) - strtotime($official->am_in))/3600,2);
        $allowance_for_pm = round((strtotime($official->pm_in) - strtotime($bio_pm_in))/3600,2);

        $am_in  = $official->am_in && $allowance_for_am <= 3 ? $bio_am_in : false;

        $am_out = $official->am_out && $allowance_for_am <= 3 && $bio_am_out > $official->am_in
                  ? $bio_am_out : false;

        $pm_in  = $official->pm_in && $allowance_for_pm <= 2 ? $bio_pm_in : false;

        $pm_out = $official->pm_in && $allowance_for_pm <= 2 && $bio_pm_out > $official->pm_in
                  ? $bio_pm_out : false;

        return (object) [
            'am_in' => $am_in,

            'am_out' =>  $am_out,

            'pm_in' => $pm_in,

            'pm_out' => $pm_out,

            'all_bio_punches' => $all_bio_punches,

            'am_render' =>  round((strtotime($am_out) - strtotime($am_in))/3600,2) < 0 ? false :
                            round((strtotime($am_out) - strtotime($am_in))/3600,2),

            'pm_render' =>  round((strtotime($pm_out) - strtotime($pm_in))/3600,2) < 0 ? false:
                            round((strtotime($pm_out) - strtotime($pm_in))/3600,2)
        ];
    }

    public function extract_bio_part_two($searched_user, $date)
    {
        $str_tc     = $searched_user->timecard;
        $str_date   = $date->format('mdy');

        $sub_updated_bio = $searched_user->update_bios->where('date', $str_date);

        // ----------------orig bio----------------------------------

        $orig_bio = Rawbio::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $searched_user->timecard)
                        ->where(DB::raw('SUBSTRING(biotext, 7, 6)'), '=', $date->format('mdy'))??false;

        $sub_orig_bio = $orig_bio->selectRaw
            ('
                SUBSTRING(biotext, 7, 6) AS date,
                SUBSTRING(biotext, 13, 4) AS hour,
                SUBSTRING(biotext, 17, 1) AS in_out,
                SUBSTRING(biotext, 1, 17) AS biotext
                ')
        ->get();


        if($sub_updated_bio->pluck('date')->contains( $date->format('mdy')))
        {
            $all_bio_punches = $sub_updated_bio;

        } else {

            $all_bio_punches = $sub_orig_bio;
        }


        return (object) [

            'processed_punch' => $all_bio_punches,
            'orig_raw_bio'  => $sub_orig_bio
        ];
    }

    public function extract_tardi($official, $day, $bio_punch)
    {

        $am_late = $official->am_in && $bio_punch->am_in && $bio_punch->am_out && $bio_punch->am_in > $official->am_in
            && $bio_punch->am_in <= $official->am_out ?
            round((strtotime($bio_punch->am_in)-strtotime($official->am_in))/3600,2) :
            0;
        $pm_late = $official->pm_in && $bio_punch->pm_in && $bio_punch->pm_out && $bio_punch->pm_in > $official->pm_in
            && $bio_punch->pm_in <= $official->pm_out?
            round((strtotime($bio_punch->pm_in)-strtotime($official->pm_in))/3600,2) :
            0;

        $am_und = $official->am_out &&  $bio_punch->am_out &&  $bio_punch->am_out >= $official->am_in
            &&  $bio_punch->am_out > $official->am_in &&  $bio_punch->am_out < $official->am_out?
            round((strtotime($official->am_out)-strtotime($bio_punch->am_out))/3600,2) :
            false;
        $pm_und = $official->pm_out &&  $bio_punch->pm_out &&  $bio_punch->pm_out >= $official->am_in
            &&  $bio_punch->pm_out >= $official->pm_in &&  $bio_punch->pm_out < $official->pm_out?
            round((strtotime($official->pm_out)-strtotime($bio_punch->pm_out))/3600,2) :
            false;


        return (object) [
            "am_late"   => $am_late > 0 ? $am_late : false,
            "pm_late"   => $pm_late> 0 ? $pm_late : false,
            // "late"      => $late> 0 ? $late : false,
            "am_und"    => $am_und>= 0 ? $am_und : false,
            "pm_und"    => $pm_und>= 0 ? $pm_und : false
        ];

    }

    public function rawbio ($rawbio)
    {
        $str_tc         = Str::limit($rawbio,6,'');
        $str_date       = substr($rawbio, 6, 6);
        $date_s         = Carbon::createFromFormat('mdy', $str_date);
        $date           = Carbon::parse($date_s);
        $searched_user  = User::where('timecard',  $str_tc)->get()[0];

        // ----------------orig bio----------------------------------

        $orig_bio = Rawbio::where(DB::raw('SUBSTRING(biotext, 1, 6)'), '=',  $searched_user->timecard)
                        ->where(DB::raw('SUBSTRING(biotext, 7, 6)'), '=', $date->format('mdy'));

        $rawbio = $orig_bio->selectRaw
            ('
                SUBSTRING(biotext, 7, 6) AS date,
                SUBSTRING(biotext, 13, 4) AS hour,
                SUBSTRING(biotext, 17, 1) AS in_out,
                SUBSTRING(punchtype_id, 1,1) AS punchtype_id
                ');


        // ----------------Official Shift----------------------------------

        $official = app()->call(ManualShiftController::class.'@official_',
            [
                'searched_user'     =>  $searched_user,
                'date'              =>  $date,
                'day'               =>  $date->format('l')
        ]);


        // ----------------Updated bio ----------------------------------

        $updated_bio = Update_bio::where('time_card',$str_tc)
        ->where('date',$str_date)->get();

        return view ('rawbio',[

            'str_tc'        =>  $str_tc ?? false,
            'str_date'      =>  $str_date ?? false,
            'searched_user' =>  $searched_user ?? false,
            'rawbio'        =>  $rawbio->with(['punchtype'])->get(),
            'orig_bio'      =>  $orig_bio->with(['punchtype'])->get(),
            'official'      =>  $official ?? false,
            'updated_bio'   =>  $updated_bio->sortBy('biotext')?? false,
            'new_input'         => session('new_input')??false,
            'validated_new_bio' => session('validated_new_bio')??false,
        ]);
    }

}
