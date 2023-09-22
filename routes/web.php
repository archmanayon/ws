<?php

namespace App\Models;

use App\Models\User;
use App\Models\ManualShift;
use App\Models\Punch;
use App\Models\Schedule;
use App\Models\Ipaddress;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UpdateBioController;
use App\Http\Controllers\PunchController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HeadController;
use App\Http\Controllers\TardiController;
use App\Http\Controllers\ExtractBioController;
use App\Http\Controllers\BiometricController;
use App\Http\Controllers\RawbioController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\IpaddressController;

use App\Http\Requests\Auth\LoginRequest;


use App\Providers\RouteServiceProvider;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


use Laravel\Socialite\Facades\Socialite;



/*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
*/

/* Sample controller by arch
    public function new_bio($bio){

        return view ('update_bio',[

            'new_trial' =>  $bio

        ]);
    }
*/

Route::get('/auth/{provider}/redirect', function ($provider) {
    return Socialite::driver($provider)->redirect();
})
->middleware(['guest'])
;

Route::get('/auth/{provider}/callback', function ($provider) {
    $user = Socialite::driver($provider)->user();

    // dd($user->email);

    if(User::where('email',$user->email)->exists()){

        // dd(User::where('email',$user->email));

        $user = User::where('email',$user->email)->get()->first();

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);

    } else {
        dd('email not registered');
    }

    // $user->token
})
->middleware(['guest'])
;

Route::get('/', function () {
    return view('welcome');
})->name('welcome_home');;

Route::get('js', function () {
    return view ('js',[
    ]);
})->middleware(['auth', 'verified', 'admin']);

Route::get('hash_pw', function () {

    // $users = User::all()->whereNotIn('username','abmanayon');
    $users = User::all()->where('username','chose name');
    // $users = User::all();

    $hashed_w_id = [];
    $corrected_name = [];

    // ---------->this updates from the column 'image_path'

        // foreach($users as $user){

        //     $hashed = Hash::make($user->student_id.'usc');
        //     $hashed_w_id[] = $user->name."'s PW is".$hashed;
        //     $corrected = str_replace('+', ',', $user->name);
        //     $corrected_name[] = $corrected;

        //     $user->update(['image_path' => Str::random(8)]);

        // }



    // ---------->this hashes
    foreach($users as $user){

        $hashed = Hash::make($user->image_path);
        $hashed_w_id[] = $user->name."'s PW is".$hashed;
        $corrected = str_replace('+', ',', $user->name);
        $corrected_name[] = $corrected;

        // $user->update(['password' => $hashed]);

        // User::where('id', $user->id)->update(['password' => $hashed]);
        // User::where('id', $user->id)->update(['name' => $corrected]);

    }

    return view ('hash_pw',[

        'hashedPassword' => $hashed_w_id,
        'corrected_name' =>$corrected_name


    ]);
})->middleware(['auth', 'verified', 'admin']);

Route::get('update_bio/{bio}', [UpdateBioController::class, 'new_bio'])
->middleware(['auth', 'verified', 'admin'])->name('new_bio');

Route::post('update_bio/{bio}', [UpdateBioController::class, 'store'])
->middleware(['auth', 'verified', 'admin'])->name('post_new_bio');

// raw Bio
Route::get('rawbio/{rawbio}', [ExtractBioController::class, 'rawbio'])
->middleware(['auth', 'verified', 'admin'])->name('show_rawbio');

Route::post('rawbio/{rawbio}', [UpdateBioController::class, 'store_rawbio'])
->middleware(['auth', 'verified', 'admin'])->name('post_rawbio');


Route::get('raw_bio_text', [BiometricController::class, 'raw_bio_text'])
->middleware(['auth', 'verified', 'admin'])->name('raw_bio_text');

Route::post('raw_bio_text', [BiometricController::class, 'raw_bio_text'])
->middleware(['auth', 'verified', 'admin'])->name('raw_bio_text_post');


Route::get('text_files', [ScheduleController::class, 'text_files'])
->middleware(['auth', 'verified', 'admin'])->name('text_files');

Route::post('text_files', [ScheduleController::class, 'text_files'])
->middleware(['auth', 'verified', 'admin'])->name('text_files_post');


Route::get('register', [RegisteredUserController::class, 'create'])
    ->middleware(['admin']);


Route::post('register', [RegisteredUserController::class, 'store'])
    ->middleware(['admin'])->name('register');


Route::get('ip_reg', [IpaddressController::class, 'create'])
    ->middleware(['admin']);


Route::post('ip_reg', [IpaddressController::class, 'store'])
    ->middleware(['admin'])->name('ip_reg');



// attendance summary per user
Route::get('dtr', [RawbioController::class, 'dtr'])
->middleware(['auth', 'verified', 'admin'])->name('dtr');

Route::post('dtr', [RawbioController::class, 'dtr'])
->middleware(['auth', 'verified', 'admin'])->name('dtr_post');

// tardiness

// 01 tardi variance the staff will conforme based on what head remarks
Route::get('tardi_variance', [TardiController::class, 'show'])
->middleware(['auth', 'verified'])
->name('show_tardi_variance');

Route::post('tardi_variance', [TardiController::class, 'conforme'])
->middleware(['auth', 'verified'])
->name('post_tardi_variance');


// 01
Route::get('tardi_group', [TardiController::class, 'tardi_group'])
->middleware(['auth', 'verified', 'head'])
->name('show_tardi_group');

// 02
Route::post('tardi_staff', [TardiController::class, 'staff_variance'])
->middleware(['auth', 'verified', 'head'])
->name('staff_variance');

// get only, won't show if no pending for head's signature
Route::get('tardi_staff', [TardiController::class, 'tardi_staff'])
->middleware(['auth', 'verified', 'head'])
->name('tardi_staff');

// 03
Route::post('tardi_group', [TardiController::class, 'post_address'])
->middleware(['auth', 'verified', 'head'])
->name('post_tardi_group')
;


// 04 tardi displays all tardiness sanction of owner
Route::get('tardi', [TardiController::class, 'show_tardi'])
->middleware(['auth', 'verified', 'staff'])
->name('show_tardi');

Route::post('tardi', [TardiController::class, 'show'])
->middleware(['auth', 'verified', 'staff'])
->name('post_tardi');

// 05 SETTING UP
Route::get('setup', [SetupController::class, 'show'])
->middleware(['auth', 'verified', 'admin'])
->name('setup_show');

Route::post('setup', [SetupController::class, 'store'])
->middleware(['auth', 'verified', 'admin'])
->name('setup_store');


// tasks
Route::get('task', [TaskController::class, 'show'])
->middleware(['auth', 'verified', 'campus',
'staff'
])
->name('show_task');

Route::post('task', [TaskController::class, 'store'])
->middleware(['auth', 'verified', 'campus',
'staff'
])
->name('store_task');

// dept head
Route::get('dept_head', [HeadController::class, 'show'])
->middleware(['auth', 'verified', 'head'])
->name('show_dept_head');

Route::post('dept_head', [TaskController::class, 'endorse'])
->middleware(['auth', 'verified', 'head'])
->name('endorse_task');

// all employee tasks
Route::get('all_employee_tasks', [TaskController::class, 'show_all_tasks'])
->middleware(['auth', 'verified', 'admin'])
->name('show_all_employee_tasks');

// Department List
Route::get('dept_list', [HeadController::class, 'show_dept_list'])
->middleware(['auth', 'verified', 'admin'])
->name('dept_list');

Route::get('history_tasks', [HeadController::class, 'show_all_tasks'])
->middleware(['auth', 'verified', 'head'])
->name('show_history_tasks');

Route::post('history_tasks', [TaskController::class, 'endorse'])
->middleware(['auth', 'verified', 'head'])
->name('endorse_history_tasks');

// shcp bio
Route::get('shcp', [PunchController::class, 'show'])
// ->middleware(['auth', 'verified'])
->name('show_punches');

Route::post('shcp', [PunchController::class, 'store'])
// ->middleware(['auth', 'verified'])
->name('punches');

// Route::get('shcp_', [PunchController::class, 'show_'])
// // ->middleware(['auth', 'verified'])
// ->name('show_punches_');

Route::post('shcp_', [PunchController::class, 'store_'])
// ->middleware(['auth', 'verified'])
->name('punches_');


Route::get('shcp_', [PunchController::class, 'show_'])
// ->middleware(['auth', 'verified'])
->name('show_punches_');


Route::get('print', [ScheduleController::class, 'absences_all'])
->middleware(['auth', 'verified', 'admin'])->name('extract');

Route::post('print', [ScheduleController::class, 'absences_all'])
->middleware(['auth', 'verified', 'admin'])->name('print_post');

Route::get('tardi_process', [TardiController::class, 'show_all'])
->middleware(['auth', 'verified', 'admin'])->name('show_all_emp_tardi');

Route::post('tardi_process', [TardiController::class, 'process'])
->middleware(['auth', 'verified', 'admin'])->name('save_all_emp_tardi');

// Route::get('/report/{ws:username}', [ScheduleController::class, 'owner_abs'])
// ->middleware(['auth', 'verified', 'scholars'])
// ->name('report');

// Route::post('/report/{ws:username}', [ScheduleController::class, 'owner_abs'])
// ->middleware(['auth', 'verified', 'scholars'])
// ->name('own_by_cal');

Route::get('report', [ScheduleController::class, 'owner_abs'])
->middleware(['auth', 'verified', 'scholars'])
->name('report');

Route::post('report', [ScheduleController::class, 'owner_abs'])
->middleware(['auth', 'verified', 'scholars',  'head'])
->name('own_by_cal');

Route::get('my_dtr', [RawbioController::class, 'my_dtr'])
->middleware(['auth', 'verified', 'staff'])->name('my_dtr');

Route::post('my_dtr', [RawbioController::class, 'my_dtr'])
->middleware(['auth', 'verified', 'staff'])->name('my_dtr_post');


Route::get('my_dtr_pdf/{selected_dates}', [RawbioController::class, 'my_dtr_pdf'])
->middleware(['auth', 'verified', 'staff'])->name('my_dtr_pdf');

Route::get('to_exel/{selected_dates}', [RawbioController::class, 'my_dtr_exel'])
->middleware(['auth', 'verified', 'staff'])->name('my_dtr_exel');

// Route::get('exel/{selected_dates}', [RawbioController::class, 'my_dtr_exel'])
// ->middleware(['auth', 'verified', 'staff'])->name('my_dtr_exel');


Route::get('adea', [ScheduleController::class, 'adea_bio_abs'])
->middleware(['auth', 'verified', 'admin'])->name('adea_get');


Route::get('employee_list', function () {
    return view ('employee_list',[
        'mappedUser' =>  User::all()
    ]);
})->middleware(['auth', 'verified', 'admin']);


Route::get('dept_employees', function () {
    return view ('dept_employees',[
        'mapped_Dept' =>  Head::all()
    ]);
})->middleware(['auth', 'verified', 'admin']);


Route::post('adea', [ScheduleController::class, 'adea_bio_abs'])
->middleware(['auth', 'verified', 'admin'])->name('adea_post');


Route::get('all_absences', [ScheduleController::class, 'print_all_abs_old'])
->middleware(['auth', 'verified', 'admin'])->name('all_absences');

Route::post('all_absences', [ScheduleController::class, 'print_all_abs_old'])
->middleware(['auth', 'verified', 'admin'])->name('disp_by_cal');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {

    //     if(Hash::check(auth()->user()->image_path, auth()->user()->password)){

    //         return redirect('profile');
    //     } else{
    //         return view('dashboard');
    //     }

// })->middleware(['auth', 'verified'])->name('dashboard');


// Route::get('/report/{ws:username}', function (User $ws) {

    //     // $sample = request()->url();
    //     // dd(basename($sample));

    //     if(auth()->user()->username == $ws->username ||
    //         auth()->user()->username == 'bitin112'){

    //         return view('report', [

    //             'user' => $ws,
    //             'punches' => Punch::all()
    //         ]);

    //     }

    //     else {
    //         return redirect('dashboard');
    //        }

// })->middleware(['auth', 'verified'])->name('report');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});





// Route::get('/extract', function () {

    //     return view('extract', [

    //         'user' => User::all(),
    //         'punches' => Punch::all(),
    //         'schedule' => Schedule::find(5),
    //         'manual_shift' => ManualShift::all()
    //     ]);

// })->middleware(['auth', 'verified', 'admin'])->name('extract');


require __DIR__.'/auth.php';
