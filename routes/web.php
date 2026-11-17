<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('guest')->group(function () {
    Route::view('/login', 'admin.login')->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attemptWhen($credentials, fn (User $user) => $user->is_admin)) {
            return back()->withErrors(['email' => 'E-mail ou senha inválidos.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    })->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin', function (Request $request) {
        abort_unless($request->user()->is_admin, 403);

        return view('admin.dashboard');
    })->name('dashboard');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});
