<?php

namespace App\Models;

use App\Models\User;
use App\Models\ManualShift;
use App\Models\Punch;
use App\Models\Schedule;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('print', [ScheduleController::class, 'print_absences'])
->middleware(['auth', 'verified', 'admin'])->name('extract');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/extract', function () {   
   
    return view('extract', [

        'user' => User::all(),
        'punches' => Punch::all(),
        'schedule' => Schedule::find(5),
        'manual_shift' => ManualShift::all()
    ]); 

})->middleware(['auth', 'verified', 'admin'])->name('extract');

Route::get('/blank', function () {   
   
    return view('blank', [

        'user' => User::all(),
        'punches' => Punch::all(),
        'schedule' => Schedule::find(5)
    ]); 

})->middleware(['auth', 'verified', 'admin'])->name('blank');


Route::get('/report/{ws:username}', function (User $ws) {

    
    // $sample = request()->url();
    // dd(basename($sample));

    if(auth()->user()->username == $ws->username ||
        auth()->user()->username == 'bitin112'){

        return view('report', [

            'user' => $ws,
            'punches' => Punch::all()
        ]);  
        
    }

    else {
        return redirect('dashboard');
       }

})->middleware(['auth', 'verified'])->name('report');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
