<?php

namespace App\Http\Controllers;

use App\Models\Blid;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class AccountLinkController extends Controller
{
    /**
     * Show the account link page.
     */
    public function show(): View
    {
        return view('account.link.index');
    }

    /**
     * TODO: Write function description.
     */
    public function store(): View
    {
        request()->validate([
            'blid' => 'required|integer|max_digits:6|bail',
        ], [
            'blid.*' => 'You must enter a valid BLID.',
        ]);

        if (RateLimiter::tooManyAttempts('failed-blid-link:'.auth()->user()->id, $perThreeMinutes = 3)) {
            return view('account.link.index')->withErrors([
                'You have too many failed attempts, please try again in a few minutes.',
            ]);
        }

        $id = request('blid');

        $blid = Blid::firstOrNew([
            'id' => $id,
        ]);

        if ($blid->exists && $blid->user_id !== null) {
            RateLimiter::hit('failed-blid-link:'.auth()->user()->id);

            return view('account.link.index')->withErrors([
                'BLID '.$id.' is already linked to an account.',
            ]);
        }

        $auth = Blid::auth($id, auth()->user()->steam_id);

        if (! $auth['success']) {
            RateLimiter::hit('failed-blid-link:'.auth()->user()->id);

            return view('account.link.index')->withErrors([
                'BLID '.$id.' is not associated with your Steam account.'.($id === '0' ? ' Very funny.' : ''),
            ]);
        }

        $name = $auth['name'];

        if ($name === null) {
            return view('account.link.index')->withErrors([
                'BLID '.$id.' does not have an in-game name set.',
            ]);
        }

        $blid->user_id = auth()->user()->id;
        $blid->name = $name;
        $blid->save();

        RateLimiter::clear('failed-blid-link:'.auth()->user()->id);

        return view('account.link.index')->with('success', 'BLID '.$id.' ('.$name.') has been successfully linked to your account.');
    }
}
