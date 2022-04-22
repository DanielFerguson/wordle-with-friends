<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::view('/', 'welcome');

Route::inertia('/dashboard', 'Dashboard')->middleware(['auth']);

// | ------------------------------------------------
// | Games
// | ------------------------------------------------

// TODO: Join/start a game - /game/{group id}/start
// * Is there a game currently open for that group? Y: join it, N: create one, join it

// | ------------------------------------------------
// | Groups
// | ------------------------------------------------

// TODO: Attempt to join a group
// TODO: 

// | ------------------------------------------------
// | Socialite (OAuth)
// | ------------------------------------------------

// Github

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
            'avatar' => $githubUser->avatar,
            'github_token' => $githubUser->token,
            'github_refresh_token' => $githubUser->refreshToken,
        ]
    );

    Auth::login($user);

    return redirect('/dashboard');
});

// Twitter

Route::get('/auth/redirect/twitter', function () {
    return Socialite::driver('twitter')->redirect();
});

Route::get('/auth/callback/twitter', function () {
    $twitterUser = Socialite::driver('twitter')->user();

    $user = User::updateOrCreate(
        ['twitter_id' => $twitterUser->id],
        [
            'name' => $twitterUser->name,
            'email' => $twitterUser->email,
            'avatar' => $twitterUser->avatar,
            'twitter_token' => $twitterUser->token,
            'twitter_refresh_token' => $twitterUser->tokenSecret,
        ]
    );

    Auth::login($user);

    return redirect('/dashboard');
});

require __DIR__ . '/auth.php';
