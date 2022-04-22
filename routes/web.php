<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::view('/', 'welcome');

Route::inertia('/dashboard', 'Dashboard');

Route::get('/auth/redirect/github', function () {
    return Socialite::driver('github')->redirect();
});

Route::get('/auth/callback/github', function () {
    $githubUser = Socialite::driver('github')->user();

    $user = User::updateOrCreate(
        ['github_id' => $githubUser->id],
        [
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'github_token' => $githubUser->token,
            'github_refresh_token' => $githubUser->refreshToken,
        ]
    );

    Auth::login($user);

    return redirect('/dashboard');
});

require __DIR__ . '/auth.php';
