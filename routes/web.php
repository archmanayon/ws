<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ManualShift;
use App\Models\Punch;
use App\Models\Schedule;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UpdateBioController;
use App\Http\Controllers\PunchController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HeadController;
use App\Http\Controllers\TardiController;
use App\Http\Controllers\ExtractBioController;
use Illuminate\Support\Facades\Route;


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

Route::get('/', function () {
    return view('welcome');
});

Route::get('js', function () {
    return view ('js',[
    ]);
});

Route::get('hash_pw', function () {

    $users = User::all()->where('role_id',3);

    $hashed_w_id = [];
    $corrected_name = [];

    foreach($users as $user){

        $hashed = Hash::make($user->student_id.'usc');
        $hashed_w_id[] = $user->name."'s PW is".$hashed;
        $corrected = str_replace('+', ',', $user->name);
        $corrected_name[] = $corrected;

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


// 03 tardi displays all tardiness sanction of owner
Route::get('tardi', [TardiController::class, 'show_tardi'])
->middleware(['auth', 'verified', 'staff'])
->name('show_tardi');

Route::post('tardi', [TardiController::class, 'show'])
->middleware(['auth', 'verified', 'staff'])
->name('post_tardi');



// tasks
Route::get('task', [TaskController::class, 'show'])
->middleware(['auth', 'verified', 'staff'])
->name('show_task');

Route::post('task', [TaskController::class, 'store'])
->middleware(['auth', 'verified', 'staff'])
->name('store_task');

// dept head
Route::get('dept_head', [HeadController::class, 'show'])
->middleware(['auth', 'verified', 'head'])
->name('show_dept_head');

Route::post('dept_head', [TaskController::class, 'endorse'])
->middleware(['auth', 'verified', 'head'])
->name('endorse_task');

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

Route::get('shcp_', [PunchController::class, 'show_'])
// ->middleware(['auth', 'verified'])
->name('show_punches_');

Route::post('shcp_', [PunchController::class, 'store_'])
// ->middleware(['auth', 'verified'])
->name('punches_');

Route::get('print', [ScheduleController::class, 'absences_all'])
->middleware(['auth', 'verified', 'admin'])->name('extract');

Route::post('print', [ScheduleController::class, 'absences_all'])
->middleware(['auth', 'verified', 'admin'])->name('print_post');

Route::get('/report/{ws:username}', [ScheduleController::class, 'owner_abs'])
->middleware(['auth', 'verified', 'scholars'])
->name('report');

Route::post('/report/{ws:username}', [ScheduleController::class, 'owner_abs'])
->middleware(['auth', 'verified', 'scholars'])
->name('own_by_cal');

Route::get('adea', [ScheduleController::class, 'adea_bio_abs'])
->middleware(['auth', 'verified', 'admin'])->name('adea_get');

Route::post('adea', [ScheduleController::class, 'adea_bio_abs'])
->middleware(['auth', 'verified', 'admin'])->name('adea_post');

Route::get('text_files', [ScheduleController::class, 'text_files'])
->middleware(['auth', 'verified', 'admin'])->name('text_files');

Route::post('text_files', [ScheduleController::class, 'text_files'])
->middleware(['auth', 'verified', 'admin'])->name('text_files_post');



Route::get('all_absences', [ScheduleController::class, 'print_all_abs_old'])
->middleware(['auth', 'verified', 'admin'])->name('all_absences');

Route::post('all_absences', [ScheduleController::class, 'print_all_abs_old'])
->middleware(['auth', 'verified', 'admin'])->name('disp_by_cal');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


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
