<?php

use App\Http\Requests\StoreGuessRequest;
use App\Models\CompetitiveGame;
use App\Models\Group;
use App\Models\Guess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
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
// | Game
// | ------------------------------------------------

// Start/load a game
Route::group(['middleware' => 'auth'], function () {
    Route::get('/play', function () {
        return Inertia::render('Play', [
            'game' => CompetitiveGame::firstOrCreate([
                'user_id' => Auth::id(),
                'date' => date('Y-m-d')
            ])->with(['guesses'])->first()
        ]);
    });

    Route::post('/play', function (StoreGuessRequest $request) {
        $validated = $request->validated();

        $game = CompetitiveGame::firstOrCreate([
            'user_id' => Auth::id(),
            'date' => date('Y-m-d')
        ])->first();

        $game->guess($validated['guess']);

        return redirect('/play');
    });
});

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
