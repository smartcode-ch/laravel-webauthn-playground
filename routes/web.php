<?php

use App\Http\Controllers\Login;
use App\Http\Controllers\Register;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/register', [Register::class, 'register'])
    ->middleware(RedirectIfAuthenticated::class);

Route::post('/register/create', [Register::class, 'create'])
    ->middleware(RedirectIfAuthenticated::class);

Route::post('/register/process', [Register::class, 'process'])
    ->middleware(RedirectIfAuthenticated::class);

Route::get('/login', [Login::class, 'login'])
    ->middleware(RedirectIfAuthenticated::class);

Route::post('/login/create', [Login::class, 'create'])
    ->middleware(RedirectIfAuthenticated::class);

Route::post('/login/process', [Login::class, 'process'])
    ->middleware(RedirectIfAuthenticated::class);

Route::get('/', fn() => Inertia::render('Home', ['user' => Auth::user()]))
    ->middleware(Authenticate::class);

Route::get('/logout', fn() => Auth::logout())
    ->middleware(Authenticate::class);
