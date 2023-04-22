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

       $bio_daily_array = Biometric::where(DB::raw('SUBSTRING(biotext, 1, 12)'), '=',  $bio);                

        $all_bio_punches = $bio_daily_array->selectRaw
            ('                
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

    public function post_new_bio(Request $request): RedirectResponse
    {
        $request->validate([
            'active' => ['required', 'boolean', 'max:1'],
            'timecard' => ['required', 'string','min:6', 'max:6'],
            'student_id' => ['required', 'string','min:8', 'max:8'],
            'name' => ['required', 'string','min:3', 'max:255'],
            'username' => ['required', 'string','min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([

            'active' => $request->active,
            'timecard' => $request->timecard,
            'student_id' => $request->student_id,
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
