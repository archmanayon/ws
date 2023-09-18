<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'active' => ['required', 'boolean', 'max:1'],
            'timecard' => ['required', 'string','min:6', 'max:6'],
            'student_id' => ['required', 'string','min:6', 'max:8'],
            'name' => ['required', 'string','min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'shift_id' => ['required', 'min:1', 'max:6'],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role_id' => ['required', 'min:1', 'max:2'],
            'head_id' => ['required', 'min:1', 'max:2'],
        ]);

        $random_pw = Str::random(8);

        $user = User::create([

            'active' => $request->active,
            'timecard' => $request->timecard,
            'student_id' => $request->student_id,
            'name' => $request->name,
            'username' => Str::beforeLast($request->email, '@'),
            'email' => $request->email,
            'password' => Hash::make($random_pw),
            'image_path' => $random_pw,
            'shift_id' => $request->shift_id,
            'role_id' => $request->role_id,
            'head_id' => $request->head_id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
