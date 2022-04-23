<?php

use App\Models\CompetitiveGame;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;

Route::view('/', 'welcome');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'groups' => Auth::user()->groups
    ]);
})->middleware(['auth']);

// | ------------------------------------------------
// | Games
// | ------------------------------------------------

// TODO: Join/start a game - /game/{group id}/start
// * Is there a game currently open for that group? Y: join it, N: create one, join it

// | ------------------------------------------------
// | Groups
// | ------------------------------------------------

Route::group(['prefix' => '/groups', 'middleware' => ['auth']], function () {
    // Create a group
    Route::get('/create', function () {
        $group = Group::create([
            'user_id' => Auth::id(),
            'private' => true,
            'type' => 'competitive',
            'join_code' => Str::random(6),
        ]);

        $group->users()->attach(Auth::user());

        return redirect('/dashboard');
    });

    // Join a group
    Route::post('/join', function (Request $request) {
        // TODO: Handle a fail by returning a failed message
        $group = Group::where('join_code', $request->code)->firstOrFail();

        $group->users()->syncWithoutDetaching(Auth::id());

        return redirect()->back();
    });

    // Play a game
    Route::get('/{group}/play', function (Group $group) {
        // Make sure the user can view this group (has joined)
        if (!$group->users->contains(function ($value, $key) {
            return $value->id === Auth::id();
        })) {
            // TODO: Return an error message to display that the user play with that group, as they have not joined.
            return redirect('/dashboard');
        }

        if ($group->type == 'competitive') {
            $competitve_game = CompetitiveGame::firstOrCreate([
                'user_id' => Auth::id(),
                'date' => date('Y-m-d')
            ]);

            return $competitve_game;
        } else if ($group->type == 'collaborative') {
            // TODO
            return false;
        }

        return true;
    });
});

// | ------------------------------------------------
// | Game
// | ------------------------------------------------

// Start/load a game
Route::group(['middleware' => 'auth'], function () {
    Route::get('/play', function () {
        $game = CompetitiveGame::firstOrCreate([
            'user_id' => Auth::id(),
            'date' => date('Y-m-d')
        ]);

        // Check if the User has a pre-existing game for today
        // If so, get all of the guesses for that game
        // Otherwise, start a new game
        return Inertia::render('Play');
    });

    // Attempt a guess
    Route::get('/attempt/{guess}', function (String $guess) {
        // Check each of the letters of the guess...
        // - if a letter exists, but is in the wrong place, it is orange,
        // - if a letter is in the right place, it is green,
        // - if a letter does not exist in the solution, it is gray
        // - if the word does not exist within the word list, return false and don't count the attempt

        dd($guess);
    });
});

// | ------------------------------------------------
// | Socialite (OAuth)
// | ------------------------------------------------

Route::group(['prefix' => '/auth/redirect'], function () {
    Route::get('/github', fn () => Socialite::driver('github')->redirect());
    Route::get('/twitter', fn () => Socialite::driver('twitter')->redirect());
});

Route::group(['prefix' => '/auth/callback'], function () {
    Route::get('/github', function () {
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

    Route::get('/twitter', function () {
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
});


require __DIR__ . '/auth.php';
