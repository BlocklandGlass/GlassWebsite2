<?php

namespace App\Http\Controllers;

use App\Models\Blid;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;

class MyAccountLinkController extends Controller
{
    /**
     * Show the account link page.
     */
    public function show(): View
    {
        $verified = false;

        if (auth()->user()->primary_blid !== null) {
            $verified = true;
        }

        return view('my-account.link.index')->with([
            'verified' => $verified,
        ]);
    }

    /**
     * TODO: Write function description.
     */
    public function store(): RedirectResponse
    {
        if (request('primary')) {
            if (auth()->user()->primary_blid !== null) {
                return back()->withErrors([
                    'You have already selected your primary BLID.',
                ]);
            }

            request()->validate([
                'primary' => 'required|integer|max_digits:6',
            ]);

            $id = request('primary');

            $blid = Blid::find($id);

            if (! $blid || $blid->user->id !== auth()->user()->id) {
                return back()->withErrors([
                    'BLID '.$id.' is not linked to your Glass account.',
                ]);
            }

            auth()->user()->primary_blid = $blid->id;
            auth()->user()->save();

            return back()->with('success', 'BLID '.$blid->id.' ('.$blid->name.') has been selected as your primary BLID.');
        }

        request()->validate([
            'blid' => 'required|integer|max_digits:6',
        ], [
            'blid.*' => 'You must enter a valid BLID.',
        ]);

        if (RateLimiter::tooManyAttempts('failed-blid-link:'.auth()->user()->id, $perThreeMinutes = 3)) {
            return back()->withErrors([
                'You have too many failed attempts, please try again in a few minutes.',
            ]);
        }

        $id = request('blid');

        $blid = Blid::firstOrNew([
            'id' => $id,
        ]);

        if ($blid->user_id) {
            RateLimiter::hit('failed-blid-link:'.auth()->user()->id);

            return back()->withErrors([
                'BLID '.$id.' is already linked.',
            ]);
        }

        $auth = Blid::auth($id, auth()->user()->steam_id);

        if (! $auth['success']) {
            RateLimiter::hit('failed-blid-link:'.auth()->user()->id);

            return back()->withErrors([
                'BLID '.$id.' is not associated with your Steam account.'.($id === '0' ? ' Very funny.' : ''),
            ]);
        }

        $name = $auth['name'];

        if ($name === null) {
            return back()->withErrors([
                'BLID '.$id.' does not have an in-game name set.',
            ]);
        }

        $blid->user_id = auth()->user()->id;
        $blid->name = $name;
        $blid->save();

        RateLimiter::clear('failed-blid-link:'.auth()->user()->id);

        return back()->with('success', 'BLID '.$id.' ('.$name.') has been successfully linked to your account.');
    }
}
